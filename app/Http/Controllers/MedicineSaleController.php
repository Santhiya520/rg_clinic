<?php
// app/Http/Controllers/MedicineSaleController.php

namespace App\Http\Controllers;

use App\Models\MedicineSale;
use App\Models\Medicine;
use App\Models\MedicineSaleItem;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MedicineSaleController extends Controller
{
        public function index(Request $request)
    {
        // Get filter values from request
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $invoiceNo = $request->get('invoice_no');
        $saleType = $request->get('sale_type');
        $customerName = $request->get('customer_name');
        $paymentStatus = $request->get('payment_status');

        // Query with filters
        $query = MedicineSale::with('items.medicine')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($fromDate) {
            $query->whereDate('sale_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('sale_date', '<=', $toDate);
        }

        if ($invoiceNo) {
            $query->where('invoice_number', 'like', "%{$invoiceNo}%");
        }

        if ($saleType && $saleType != '') {
            $query->where('type', $saleType);
        }

        if ($customerName) {
            $query->where(function($q) use ($customerName) {
                $q->where('customer_name', 'like', "%{$customerName}%")
                  ->orWhere('department_name', 'like', "%{$customerName}%");
            });
        }

        if ($paymentStatus && $paymentStatus != '') {
            if ($paymentStatus == 'internal') {
                $query->where('is_internal', true);
            } else {
                $query->where('payment_status', $paymentStatus)
                      ->where('is_internal', false);
            }
        }

        $sales = $query->get();

        // Calculate totals for summary
        $totalAmount = $query->sum('grand_total');
        $totalDiscount = $query->sum('total_discount');
        $totalReceived = $query->sum('paid_amount');
        $totalBalance = $query->sum('due_amount');

        return view('medicine-sales.index', compact(
            'sales',
            'fromDate',
            'toDate',
            'invoiceNo',
            'saleType',
            'customerName',
            'paymentStatus',
            'totalAmount',
            'totalDiscount',
            'totalReceived',
            'totalBalance'
        ));
    }

    public function create()
    {
        $medicines = Medicine::where('stock', '>', 0)->get();
        $invoiceNumber = 'SALE-' . date('Ymd') . '-' . str_pad((MedicineSale::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        return view('medicine-sales.create', compact('medicines', 'invoiceNumber'));
    }

    public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $isInternal = in_array($request->type, ['radiology-use', 'lab-use', 'other']);

        $rules = [
            'invoice_number' => 'required|unique:medicine_sales',
            'type' => 'required|in:customer,radiology-use,lab-use,other',
            'sale_date' => 'required|date',
            'notes' => 'nullable|string',
            'injection_fees' => 'nullable|numeric|min:0',
            'procedure_fees' => 'nullable|numeric|min:0',
            'overall_discount_percent' => 'nullable|numeric|min:0|max:100',
            'overall_discount_amount' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.medicine_id' => 'nullable|exists:medicines,id',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ];

        // Conditional rules
        if ($isInternal) {
            $rules['department'] = 'nullable|string|max:255';
        } else {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_phone'] = 'nullable|string|max:20';
            $rules['customer_address'] = 'nullable|string';
            $rules['payment_status'] = 'required|in:paid,partial,due';
            $rules['payment_method'] = 'required|in:cash,card,upi,cheque';
            $rules['paid_amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // Calculate totals
        $subTotal = 0;
        $totalItemDiscount = 0;
        $itemsData = [];

        if ($request->items && count($request->items) > 0) {
            foreach ($request->items as $item) {
                if (empty($item['medicine_id'])) {
                    continue;
                }

                $medicine = Medicine::findOrFail($item['medicine_id']);

                // Check stock availability
                if ($medicine->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$medicine->stock}");
                }

                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $discountPercent = $item['discount_percent'] ?? 0;

                // Calculate item amounts
                $originalAmount = $quantity * $unitPrice;
                $discountAmount = ($originalAmount * $discountPercent) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                $subTotal += $originalAmount;
                $totalItemDiscount += $discountAmount;

                $itemsData[] = [
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                ];

                // Reduce stock
                $medicine->stock -= $quantity;
                $medicine->save();
            }
        }

        // Calculate amount after item discount
        $amountAfterItemDiscount = $subTotal - $totalItemDiscount;

        // Apply overall discount
        $overallDiscountPercent = $request->overall_discount_percent ?? 0;
        $overallDiscountAmount = ($amountAfterItemDiscount * $overallDiscountPercent) / 100;

        // Amount after all discounts (medicine amount only)
        $amountAfterAllDiscounts = $amountAfterItemDiscount - $overallDiscountAmount;

        // Add injection and procedure fees (NO GST)
        $injectionFees = $request->injection_fees ?? 0;
        $procedureFees = $request->procedure_fees ?? 0;

        // Calculate grand total before round off
        $grandTotalBeforeRound = $amountAfterAllDiscounts + $injectionFees + $procedureFees;

        // Apply round off to nearest rupee
        $roundedGrandTotal = round($grandTotalBeforeRound);

        // For internal use, set specific values
        if ($isInternal) {
            $paidAmount = 0;
            $dueAmount = 0;
            $paymentStatus = 'internal';
            $paymentMethod = 'internal';
            $customerName = null;
            $customerPhone = null;
            $customerAddress = null;
        } else {
            $paidAmount = $request->paid_amount ?? 0;
            $dueAmount = $roundedGrandTotal - $paidAmount;
            $paymentStatus = $request->payment_status;
            $paymentMethod = $request->payment_method;
            $customerName = $request->customer_name;
            $customerPhone = $request->customer_phone;
            $customerAddress = $request->customer_address;
        }

        // Prepare sale data array (only include columns that exist)
        $saleData = [
            'invoice_number' => $request->invoice_number,
            'type' => $request->type,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'customer_address' => $customerAddress,
            'department' => $isInternal ? ($request->department ?? strtoupper(substr($request->type, 0, 3))) : null,
            'sale_date' => $request->sale_date,
            'sub_total' => $subTotal,
            'total_discount' => $totalItemDiscount + $overallDiscountAmount,
            'injection_fees' => $injectionFees,
            'procedure_fees' => $procedureFees,
            'overall_discount_percent' => $overallDiscountPercent,
            'overall_discount_amount' => $overallDiscountAmount,
            'grand_total' => $roundedGrandTotal,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ];

        // Remove GST related fields as they're not needed
        // Create sale
        $sale = MedicineSale::create($saleData);

        // Create sale items
        foreach ($itemsData as $itemData) {
            $sale->items()->create($itemData);
        }

        DB::commit();

        return redirect()->route('medicine-sales.show', $sale)
            ->with('success', $isInternal ? 'Internal medicine use recorded!' : 'Medicine sale created!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}
    public function show(MedicineSale $medicineSale)
    {
        $medicineSale->load('items.medicine', 'user');
        return view('medicine-sales.show', compact('medicineSale'));
    }

    public function edit(MedicineSale $medicineSale)
    {
        $medicines = Medicine::all();
        return view('medicine-sales.edit', compact('medicineSale', 'medicines'));
    }

    public function update(Request $request, MedicineSale $medicineSale)
{
    DB::beginTransaction();

    try {
        $isInternal = in_array($medicineSale->type, ['radiology-use', 'lab-use', 'other']);

        $rules = [
            'sale_date' => 'required|date',
            'notes' => 'nullable|string',
            'injection_fees' => 'nullable|numeric|min:0',
            'procedure_fees' => 'nullable|numeric|min:0',
            'overall_discount_percent' => 'nullable|numeric|min:0|max:100',
            'overall_discount_amount' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.medicine_id' => 'nullable|exists:medicines,id',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ];

        if ($isInternal) {
            $rules['department'] = 'nullable|string|max:255';
        } else {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_phone'] = 'nullable|string|max:20';
            $rules['customer_address'] = 'nullable|string';
            $rules['payment_status'] = 'required|in:paid,partial,due';
            $rules['payment_method'] = 'required|in:cash,card,upi,cheque';
            $rules['paid_amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // Restore old stock
        foreach ($medicineSale->items as $oldItem) {
            $medicine = Medicine::find($oldItem->medicine_id);
            if ($medicine) {
                $medicine->stock += $oldItem->quantity;
                $medicine->save();
            }
        }

        // Delete old items
        $medicineSale->items()->delete();

        // Calculate new totals
        $subTotal = 0;
        $totalItemDiscount = 0;
        $itemsData = [];

        if ($request->items && count($request->items) > 0) {
            foreach ($request->items as $item) {
                if (empty($item['medicine_id'])) {
                    continue;
                }

                $medicine = Medicine::findOrFail($item['medicine_id']);

                // Check stock availability
                if ($medicine->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$medicine->name}. Available: {$medicine->stock}");
                }

                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $discountPercent = $item['discount_percent'] ?? 0;

                // Calculate item amounts
                $originalAmount = $quantity * $unitPrice;
                $discountAmount = ($originalAmount * $discountPercent) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                $subTotal += $originalAmount;
                $totalItemDiscount += $discountAmount;

                $itemsData[] = [
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                ];

                // Reduce stock
                $medicine->stock -= $quantity;
                $medicine->save();
            }
        }

        // Calculate overall discount
        $overallDiscountPercent = $request->overall_discount_percent ?? 0;
        $amountAfterItemDiscount = $subTotal - $totalItemDiscount;
        $overallDiscountAmount = ($amountAfterItemDiscount * $overallDiscountPercent) / 100;

        // Amount after all discounts (NO GST)
        $amountAfterAllDiscounts = $amountAfterItemDiscount - $overallDiscountAmount;

        // Add fees
        $injectionFees = $request->injection_fees ?? 0;
        $procedureFees = $request->procedure_fees ?? 0;

        // Calculate grand total (NO GST)
        $grandTotalBeforeRound = $amountAfterAllDiscounts + $injectionFees + $procedureFees;
        $roundedGrandTotal = round($grandTotalBeforeRound);

        // Prepare update data
        $updateData = [
            'sale_date' => $request->sale_date,
            'sub_total' => $subTotal,
            'total_discount' => $totalItemDiscount + $overallDiscountAmount,
            'injection_fees' => $injectionFees,
            'procedure_fees' => $procedureFees,
            'overall_discount_percent' => $overallDiscountPercent,
            'overall_discount_amount' => $overallDiscountAmount,
            'grand_total' => $roundedGrandTotal,
            'notes' => $request->notes,
        ];

        if ($isInternal) {
            $updateData['department'] = $request->department ?? strtoupper(substr($medicineSale->type, 0, 3));
            $updateData['paid_amount'] = 0;
            $updateData['due_amount'] = 0;
        } else {
            $paidAmount = $request->paid_amount ?? 0;
            $dueAmount = $roundedGrandTotal - $paidAmount;

            $updateData['customer_name'] = $request->customer_name;
            $updateData['customer_phone'] = $request->customer_phone;
            $updateData['customer_address'] = $request->customer_address;
            $updateData['paid_amount'] = $paidAmount;
            $updateData['due_amount'] = $dueAmount;
            $updateData['payment_status'] = $request->payment_status;
            $updateData['payment_method'] = $request->payment_method;
        }

        $medicineSale->update($updateData);

        // Create new sale items
        foreach ($itemsData as $itemData) {
            $medicineSale->items()->create($itemData);
        }

        DB::commit();

        return redirect()->route('medicine-sales.show', $medicineSale)
            ->with('success', $isInternal ? 'Internal use updated!' : 'Sale updated!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}

    public function destroy(MedicineSale $medicineSale)
    {
        DB::beginTransaction();

        try {
            // Restore stock for all items
            foreach ($medicineSale->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                if ($medicine) {
                    $medicine->stock += $item->quantity;
                    $medicine->save();
                }
            }

            $medicineSale->delete();

            DB::commit();

            return redirect()->route('medicine-sales.index')
                ->with('success', ($medicineSale->is_internal ? 'Internal use' : 'Sale') . ' deleted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function print(MedicineSale $medicineSale)
    {
        $medicineSale->load('items.medicine');
        return view('medicine-sales.print', compact('medicineSale'));
    }
}
