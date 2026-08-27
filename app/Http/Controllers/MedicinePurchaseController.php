<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\StringHelper;

class MedicinePurchaseController extends Controller
{
    // Display all purchases
        public function index(Request $request)
    {
        // Get filter values from request
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $invoiceNo = $request->get('invoice_no');
        $supplierName = $request->get('supplier_name');
        $paymentStatus = $request->get('payment_status');

        // Query with filters
        $query = MedicinePurchase::with('items.medicine')
            ->latest();

        // Apply filters
        if ($fromDate) {
            $query->whereDate('purchase_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('purchase_date', '<=', $toDate);
        }

        if ($invoiceNo) {
            $query->where('invoice_number', 'like', "%{$invoiceNo}%");
        }

        if ($supplierName) {
            $query->where('supplier_name', 'like', "%{$supplierName}%");
        }

        if ($paymentStatus && $paymentStatus != '') {
            $query->where('payment_status', $paymentStatus);
        }

        $purchases = $query->paginate(15); // Add pagination

        // Calculate totals for summary
        $totalAmount = $query->sum('total_amount');
        $totalPaid = $query->sum('paid_amount');
        $totalDue = $query->sum('due_amount');

        return view('medicine-purchases.index', compact(
            'purchases',
            'fromDate',
            'toDate',
            'invoiceNo',
            'supplierName',
            'paymentStatus',
            'totalAmount',
            'totalPaid',
            'totalDue'
        ));
    }

    // Show create form
    public function create()
    {
        // Get all active suppliers
        $suppliers = Supplier::active()->orderBy('name')->get();
        $invoiceNumber = "";

        return view('medicine-purchases.create', compact('suppliers', 'invoiceNumber'));
    }

    // Get medicines by supplier (AJAX)
    public function getMedicinesBySupplier($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            $medicines = Medicine::active()
                ->bySupplier($supplierId)
                ->orderBy('name')
                ->get();

            // Decode medicine names for display
            $medicines = $medicines->map(function ($medicine) {
                $medicine->decoded_name = StringHelper::decodeQuotes($medicine->name);
                return $medicine;
            });

            return response()->json([
                'success' => true,
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => StringHelper::decodeQuotes($supplier->name),
                    'address' => $supplier->address,
                    'phone' => $supplier->phone
                ],
                'medicines' => $medicines
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load medicines: ' . $e->getMessage()
            ], 500);
        }
    }

    // Store new purchase
    public function store(Request $request)
    {
        \Log::info('=== PURCHASE STORE START ===');
        \Log::info('Raw Request Data:', $request->all());

        try {
            // Manual validation
            $validator = \Validator::make($request->all(), [
                'invoice_number' => 'required|string',
                'purchase_date' => 'required|date',
                'supplier_id' => 'required|exists:suppliers,id',
                'supplier_name' => 'required|string|max:255',
                'supplier_phone' => 'nullable|string|max:20',
                'supplier_address' => 'nullable|string|max:500',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'due_amount' => 'required|numeric|min:0',
                'payment_status' => 'required|in:paid,partial,due',
                'notes' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.medicine_id' => 'required|exists:medicines,id',
                'items.*.batch_number' => 'required|string|max:100',
                'items.*.expiry_date' => 'required|string', // MM/YYYY format
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.total_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation Errors:', $validator->errors()->toArray());
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();



            \Log::info('Validated Data Successfully');

            DB::beginTransaction();

            // Encode supplier name before saving
            $supplierNameEncoded = StringHelper::encodeQuotes($validated['supplier_name']);

            // Create purchase
            $purchaseData = [
                'invoice_number' => $validated['invoice_number'],
                'purchase_date' => $validated['purchase_date'],
                'supplier_id' => $validated['supplier_id'],
                'supplier_name' => $supplierNameEncoded,
                'supplier_phone' => $validated['supplier_phone'] ?? null,
                'supplier_address' => $validated['supplier_address'] ?? null,
                'total_amount' => $validated['total_amount'],
                'paid_amount' => $validated['paid_amount'],
                'due_amount' => $validated['due_amount'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'] ?? null,
            ];

            \Log::info('Creating Purchase Record:', $purchaseData);

            $purchase = MedicinePurchase::create($purchaseData);
            \Log::info('Purchase Created with ID: ' . $purchase->id);

            // Create purchase items
            foreach ($validated['items'] as $index => $itemData) {
                \Log::info("Creating Purchase Item {$index}:", $itemData);

                $purchaseItemData = [
                    'medicine_purchase_id' => $purchase->id,
                    'medicine_id' => $itemData['medicine_id'],
                    'batch_number' => $itemData['batch_number'],
                    'expiry_date' => $itemData['expiry_date'], // Store as MM/YYYY format
                    'quantity' => $itemData['quantity'],
                    'purchase_price' => $itemData['purchase_price'],
                    'total_amount' => $itemData['total_amount'],
                ];

                $purchaseItem = MedicinePurchaseItem::create($purchaseItemData);
                \Log::info("Purchase Item {$index} Created with ID: " . $purchaseItem->id);

                // Update medicine stock
                $medicine = Medicine::find($itemData['medicine_id']);
                if ($medicine) {
                    $oldStock = $medicine->stock;
                    $newStock = $oldStock + $itemData['quantity'];
                    $medicine->update(['stock' => $newStock]);
                    \Log::info("Stock updated for medicine ID {$medicine->id}: {$oldStock} → {$newStock}");
                } else {
                    \Log::warning("Medicine not found with ID: " . $itemData['medicine_id']);
                }
            }

            DB::commit();
            \Log::info('=== PURCHASE STORE COMPLETED SUCCESSFULLY ===');

            return redirect()->route('medicine-purchases.index')
                ->with('success', 'Medicine purchase added successfully. Stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('STORE ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // Show purchase details
    public function show(MedicinePurchase $medicinePurchase)
    {
        $medicinePurchase->load('items.medicine');

        // Decode supplier name for display
        $medicinePurchase->supplier_name_decoded = StringHelper::decodeQuotes($medicinePurchase->supplier_name);

        return view('medicine-purchases.show', compact('medicinePurchase'));
    }

    public function edit(MedicinePurchase $medicinePurchase)
    {
        $suppliers = Supplier::active()
            ->orderBy('name')
            ->get();

        $medicines = Medicine::active()
            ->bySupplier($medicinePurchase->supplier_id)
            ->orderBy('name')
            ->get()
            ->map(function ($medicine) {
                return [
                    'id' => $medicine->id,
                    'decoded_name' => \App\Helpers\StringHelper::decodeQuotes($medicine->name),
                    'price' => $medicine->price,
                    'stock' => $medicine->stock,
                    'category' => $medicine->category,
                ];
            });

        $medicinePurchase->supplier_name_decoded =
            \App\Helpers\StringHelper::decodeQuotes($medicinePurchase->supplier_name);

        $medicinePurchase->load('items.medicine');

        return view(
            'medicine-purchases.edit',
            compact('medicinePurchase', 'suppliers', 'medicines')
        );
    }


    public function update(Request $request, MedicinePurchase $medicinePurchase)
    {
        \Log::info('=== PURCHASE UPDATE START ===');
        \Log::info('Raw Request Data:', $request->all());

        try {
            $validator = \Validator::make($request->all(), [
                'invoice_number' => 'required|string|unique:medicine_purchases,invoice_number,' . $medicinePurchase->id,
                'purchase_date' => 'required|date',
                'supplier_id' => 'required|exists:suppliers,id',
                'supplier_name' => 'required|string|max:255',
                'supplier_phone' => 'nullable|string|max:20',
                'supplier_address' => 'nullable|string|max:500',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'due_amount' => 'required|numeric|min:0',
                'payment_status' => 'required|in:paid,partial,due',
                'notes' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.medicine_id' => 'required|exists:medicines,id',
                'items.*.batch_number' => 'required|string|max:100',
                'items.*.expiry_date' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/([0-9]{4})$/'], // Use array format for regex
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.total_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation Errors:', $validator->errors()->toArray());
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();
            \Log::info('Validated Data Successfully');

            DB::beginTransaction();

            // Encode supplier name before updating
            $supplierNameEncoded = StringHelper::encodeQuotes($validated['supplier_name']);

            // Log old items for debugging
            $oldItems = [];
            foreach ($medicinePurchase->items as $item) {
                $oldItems[$item->id] = [
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'batch_number' => $item->batch_number
                ];
            }
            \Log::info('Old items to be replaced:', $oldItems);

            // Update purchase
            $purchaseData = [
                'invoice_number' => $validated['invoice_number'],
                'purchase_date' => $validated['purchase_date'],
                'supplier_id' => $validated['supplier_id'],
                'supplier_name' => $supplierNameEncoded,
                'supplier_phone' => $validated['supplier_phone'] ?? null,
                'supplier_address' => $validated['supplier_address'] ?? null,
                'total_amount' => $validated['total_amount'],
                'paid_amount' => $validated['paid_amount'],
                'due_amount' => $validated['due_amount'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'] ?? null,
            ];

            \Log::info('Updating Purchase Record:', $purchaseData);
            $medicinePurchase->update($purchaseData);

            // STEP 1: Remove old stock quantities (reverse the original purchase)
            foreach ($medicinePurchase->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                if ($medicine) {
                    $oldStock = $medicine->stock;
                    $medicine->decrement('stock', $item->quantity);
                    \Log::info("Stock removed for medicine {$medicine->id}: {$oldStock} → {$medicine->stock} (-{$item->quantity})");
                } else {
                    \Log::warning("Medicine not found when removing stock for item ID: " . $item->id);
                }
            }

            // STEP 2: Delete old items
            $medicinePurchase->items()->delete();
            \Log::info('Old items deleted');

            // STEP 3: Create new items and add new stock quantities
            foreach ($validated['items'] as $index => $itemData) {
                \Log::info("Creating Purchase Item {$index}:", $itemData);

                $purchaseItemData = [
                    'medicine_purchase_id' => $medicinePurchase->id,
                    'medicine_id' => $itemData['medicine_id'],
                    'batch_number' => $itemData['batch_number'],
                    'expiry_date' => $itemData['expiry_date'], // Store as MM/YYYY string
                    'quantity' => $itemData['quantity'],
                    'purchase_price' => $itemData['purchase_price'],
                    'total_amount' => $itemData['total_amount'],
                ];

                $purchaseItem = MedicinePurchaseItem::create($purchaseItemData);
                \Log::info("Purchase Item {$index} Created with ID: " . $purchaseItem->id);

                // Add new stock quantities
                $medicine = Medicine::find($itemData['medicine_id']);
                if ($medicine) {
                    $oldStock = $medicine->stock;
                    $medicine->increment('stock', $itemData['quantity']);
                    \Log::info("Stock added for medicine {$medicine->id}: {$oldStock} → {$medicine->stock} (+{$itemData['quantity']})");
                } else {
                    \Log::warning("Medicine not found with ID: " . $itemData['medicine_id']);
                }
            }

            DB::commit();
            \Log::info('=== PURCHASE UPDATE COMPLETED SUCCESSFULLY ===');

            return redirect()->route('medicine-purchases.index')
                ->with('success', 'Medicine purchase updated successfully. Stock adjusted.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('UPDATE ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }



    // Delete purchase
    public function destroy(MedicinePurchase $medicinePurchase)
    {
        try {
            DB::beginTransaction();

            // Restore stock before deleting
            foreach ($medicinePurchase->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                $medicine->update([
                    'stock' => $medicine->stock - $item->quantity
                ]);
            }

            $medicinePurchase->delete();

            DB::commit();

            return redirect()->route('medicine-purchases.index')
                ->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('medicine-purchases.index')
                ->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }

    public function stockReport()
    {
        try {
            // Get all medicines with calculated purchase price (average from purchases)
            $medicines = Medicine::select(
                'id',
                'name',
                'description',
                'category',
                'stock'
            )
                ->orderBy('name')
                ->get();

            // Calculate average purchase price for each medicine
            foreach ($medicines as $medicine) {
                $avgPrice = DB::table('medicine_purchase_items')
                    ->where('medicine_id', $medicine->id)
                    ->avg('purchase_price');

                $medicine->purchase_price = $avgPrice ?: 0;
            }

            // Get purchase transactions
            $purchases = DB::table('medicine_purchase_items as mpi')
                ->join('medicine_purchases as mp', 'mpi.medicine_purchase_id', '=', 'mp.id')
                ->join('medicines as m', 'mpi.medicine_id', '=', 'm.id')
                ->select(
                    'mpi.medicine_id',
                    'm.name as medicine_name',
                    'mpi.batch_number',
                    'mpi.expiry_date',
                    'mpi.quantity',
                    'mpi.purchase_price',
                    'mp.purchase_date as transaction_date',
                    DB::raw("'PURCHASE' as transaction_type"),
                    DB::raw("CONCAT('PUR-', mp.invoice_number) as reference")
                )
                ->get();

            // Get all sale transactions (OP, IP, and direct sales)

            // 1. Get OP medicine sales
            $opSales = DB::table('op_medicines as op')
                ->join('op_registers as opr', 'op.op_register_id', '=', 'opr.id')
                ->join('medicines as m', 'op.medicine_id', '=', 'm.id')
                ->select(
                    'op.medicine_id',
                    'm.name as medicine_name',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'op.quantity',
                    'op.price as purchase_price',
                    'opr.created_at as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('OP-', opr.id) as reference")
                )
                ->whereNotNull('op.issued_at')
                ->get();

            // 2. Get IP medicine sales
            $ipSales = DB::table('ip_medicines as ip')
                ->join('inpatient_registers as ipr', 'ip.inpatient_register_id', '=', 'ipr.id')
                ->join('medicines as m', 'ip.medicine_id', '=', 'm.id')
                ->select(
                    'ip.medicine_id',
                    'm.name as medicine_name',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'ip.quantity',
                    'ip.price as purchase_price',
                    'ipr.created_at as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('IP-', ipr.id) as reference")
                )
                ->whereNotNull('ip.issued_at')
                ->get();

            // 3. Get direct medicine sales (from medicine_sale_items)
            $directSales = DB::table('medicine_sale_items as msi')
                ->join('medicine_sales as ms', 'msi.medicine_sale_id', '=', 'ms.id')
                ->join('medicines as m', 'msi.medicine_id', '=', 'm.id')
                ->select(
                    'msi.medicine_id',
                    'm.name as medicine_name',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'msi.quantity',
                    'msi.unit_price as purchase_price',
                    'ms.sale_date as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('SALE-', ms.invoice_number) as reference")
                )
                ->get();

            // Combine all sales
            $sales = $opSales->merge($ipSales)->merge($directSales);

            // Combine all transactions
            $allTransactions = $purchases->merge($sales)->sortByDesc('transaction_date');

            // Calculate stock value and transaction counts for each medicine
            $medicines->each(function ($medicine) use ($purchases, $sales) {
                $medicine->stock_value = $medicine->stock * $medicine->purchase_price;

                $medicine->purchase_count = $purchases->where('medicine_id', $medicine->id)->count();
                $medicine->sale_count = $sales->where('medicine_id', $medicine->id)->count();
                $medicine->total_transactions = $medicine->purchase_count + $medicine->sale_count;
            });

            // Calculate totals
            $totalStockValue = $medicines->sum('stock_value');
            $totalMedicines = $medicines->count();
            $totalPurchases = $purchases->count();
            $totalSales = $sales->count();

            return view('medicine-purchases.stock-report', compact(
                'medicines',
                'allTransactions',
                'totalStockValue',
                'totalMedicines',
                'totalPurchases',
                'totalSales'
            ));
        } catch (\Exception $e) {
            \Log::error('Stock Report Error: ' . $e->getMessage());
            return redirect()->route('medicine-purchases.index')
                ->with('error', 'Failed to generate stock report: ' . $e->getMessage());
        }
    }

    public function expiryReport(Request $request)
    {
        try {
            $filter = $request->get('filter', 'all');
            $category = $request->get('category', '');
            $days = intval($request->get('days', 90));

            $query = Medicine::select(
                'id',
                'name',
                'description',
                'category',
                'stock',
                'expiry_date',
                'price',
                'status',
                'supplier_id'
            )->with('supplier');

            if ($category) {
                $query->where('category', $category);
            }

            $medicines = $query->orderBy('expiry_date', 'asc')->get();

            $today = now()->startOfDay();

            $expired = $medicines->filter(function ($m) use ($today) {
                return $m->expiry_date && $m->expiry_date->startOfDay()->lt($today);
            });

            $nearExpiry = $medicines->filter(function ($m) use ($today, $days) {
                return $m->expiry_date &&
                    $m->expiry_date->startOfDay()->gte($today) &&
                    $m->expiry_date->startOfDay()->lte($today->copy()->addDays($days));
            });

            $valid = $medicines->filter(function ($m) use ($today, $days) {
                return $m->expiry_date &&
                    $m->expiry_date->startOfDay()->gt($today->copy()->addDays($days));
            });

            if ($filter === 'expired') {
                $filteredMedicines = $expired;
            } elseif ($filter === 'near_expiry') {
                $filteredMedicines = $nearExpiry;
            } else {
                $filteredMedicines = $medicines;
            }

            $totalStockValue = $filteredMedicines->sum(function ($m) {
                return $m->stock * $m->price;
            });

            $expiredStockValue = $expired->sum(function ($m) {
                return $m->stock * $m->price;
            });

            $categories = Medicine::CATEGORIES;

            return view('medicine-purchases.expiry-report', compact(
                'filteredMedicines',
                'expired',
                'nearExpiry',
                'valid',
                'filter',
                'category',
                'days',
                'totalStockValue',
                'expiredStockValue',
                'categories'
            ));
        } catch (\Exception $e) {
            \Log::error('Expiry Report Error: ' . $e->getMessage());
            return redirect()->route('medicine-purchases.index')
                ->with('error', 'Failed to generate expiry report: ' . $e->getMessage());
        }
    }

    public function medicineTransactions($medicineId)
    {
        try {
            $medicine = Medicine::findOrFail($medicineId);

            // Get average purchase price
            $avgPurchasePrice = DB::table('medicine_purchase_items')
                ->where('medicine_id', $medicineId)
                ->avg('purchase_price');

            $medicine->purchase_price = $avgPurchasePrice ?: 0;

            // Get purchase transactions for this medicine
            $purchases = DB::table('medicine_purchase_items as mpi')
                ->join('medicine_purchases as mp', 'mpi.medicine_purchase_id', '=', 'mp.id')
                ->select(
                    'mpi.medicine_id',
                    'mpi.batch_number',
                    'mpi.expiry_date',
                    'mpi.quantity',
                    'mpi.purchase_price',
                    'mp.purchase_date as transaction_date',
                    DB::raw("'PURCHASE' as transaction_type"),
                    DB::raw("CONCAT('PUR-', mp.invoice_number) as reference"),
                    DB::raw("mpi.quantity as stock_change")
                )
                ->where('mpi.medicine_id', $medicineId)
                ->get();

            // Get all sale transactions for this medicine

            // 1. OP medicine sales
            $opSales = DB::table('op_medicines as op')
                ->join('op_registers as opr', 'op.op_register_id', '=', 'opr.id')
                ->select(
                    'op.medicine_id',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'op.quantity',
                    'op.price as purchase_price',
                    'opr.created_at as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('OP-', opr.id) as reference"),
                    DB::raw("op.quantity * -1 as stock_change")
                )
                ->where('op.medicine_id', $medicineId)
                ->whereNotNull('op.issued_at')
                ->get();

            // 2. IP medicine sales
            $ipSales = DB::table('ip_medicines as ip')
                ->join('inpatient_registers as ipr', 'ip.inpatient_register_id', '=', 'ipr.id')
                ->select(
                    'ip.medicine_id',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'ip.quantity',
                    'ip.price as purchase_price',
                    'ipr.created_at as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('IP-', ipr.id) as reference"),
                    DB::raw("ip.quantity * -1 as stock_change")
                )
                ->where('ip.medicine_id', $medicineId)
                ->whereNotNull('ip.issued_at')
                ->get();

            // 3. Direct medicine sales
            $directSales = DB::table('medicine_sale_items as msi')
                ->join('medicine_sales as ms', 'msi.medicine_sale_id', '=', 'ms.id')
                ->select(
                    'msi.medicine_id',
                    'msi.quantity',
                    'msi.unit_price as purchase_price',
                    'ms.sale_date as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('SALE-', ms.invoice_number) as reference"),
                    DB::raw("msi.quantity * -1 as stock_change")
                )
                ->where('msi.medicine_id', $medicineId)
                ->get();

            // Combine all sales
            $sales = $opSales->merge($ipSales)->merge($directSales);

            // Combine and sort by date descending (newest first)
            $transactions = $purchases->merge($sales)
                ->sortByDesc('transaction_date')
                ->values();

            return view('medicine-purchases.medicine-transactions', compact(
                'medicine',
                'transactions'
            ));
        } catch (\Exception $e) {
            dd($e);
            \Log::error('Medicine Transactions Error: ' . $e->getMessage());
            return redirect()->route('medicine-purchases.stock-report')
                ->with('error', 'Failed to load medicine transactions: ' . $e->getMessage());
        }
    }

    public function medicineTransactionsPrint($medicineId)
    {
        try {
            $medicine = Medicine::findOrFail($medicineId);

            // Get average purchase price
            $avgPurchasePrice = DB::table('medicine_purchase_items')
                ->where('medicine_id', $medicineId)
                ->avg('purchase_price');

            $medicine->purchase_price = $avgPurchasePrice ?: 0;

            // Get purchase transactions for this medicine
            $purchases = DB::table('medicine_purchase_items as mpi')
                ->join('medicine_purchases as mp', 'mpi.medicine_purchase_id', '=', 'mp.id')
                ->select(
                    'mpi.medicine_id',
                    'mpi.batch_number',
                    'mpi.expiry_date',
                    'mpi.quantity',
                    'mpi.purchase_price',
                    'mp.purchase_date as transaction_date',
                    DB::raw("'PURCHASE' as transaction_type"),
                    DB::raw("CONCAT('PUR-', mp.invoice_number) as reference"),
                    DB::raw("mpi.quantity as stock_change")
                )
                ->where('mpi.medicine_id', $medicineId)
                ->get();

            // Get all sale transactions for this medicine

            // 1. OP medicine sales
            $opSales = DB::table('op_medicines as op')
                ->join('op_registers as opr', 'op.op_register_id', '=', 'opr.id')
                ->select(
                    'op.medicine_id',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'op.quantity',
                    'op.price as purchase_price',
                    'opr.created_at as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('OP-', opr.id) as reference"),
                    DB::raw("op.quantity * -1 as stock_change")
                )
                ->where('op.medicine_id', $medicineId)
                ->whereNotNull('op.issued_at')
                ->get();

            // 2. IP medicine sales
            $ipSales = DB::table('ip_medicines as ip')
                ->join('inpatient_registers as ipr', 'ip.inpatient_register_id', '=', 'ipr.id')
                ->select(
                    'ip.medicine_id',
                    DB::raw("NULL as batch_number"),
                    DB::raw("NULL as expiry_date"),
                    'ip.quantity',
                    'ip.price as purchase_price',
                    'ipr.created_at as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('IP-', ipr.id) as reference"),
                    DB::raw("ip.quantity * -1 as stock_change")
                )
                ->where('ip.medicine_id', $medicineId)
                ->whereNotNull('ip.issued_at')
                ->get();

            // 3. Direct medicine sales
            $directSales = DB::table('medicine_sale_items as msi')
                ->join('medicine_sales as ms', 'msi.medicine_sale_id', '=', 'ms.id')
                ->select(
                    'msi.medicine_id',
                    'msi.quantity',
                    'msi.unit_price as purchase_price',
                    'ms.sale_date as transaction_date',
                    DB::raw("'SALE' as transaction_type"),
                    DB::raw("CONCAT('SALE-', ms.invoice_number) as reference"),
                    DB::raw("msi.quantity * -1 as stock_change")
                )
                ->where('msi.medicine_id', $medicineId)
                ->get();

            // Combine all sales
            $sales = $opSales->merge($ipSales)->merge($directSales);

            // Combine and sort by date descending (newest first)
            $transactions = $purchases->merge($sales)
                ->sortByDesc('transaction_date')
                ->values();

            return view('medicine-purchases.medicine-transactions-print', compact(
                'medicine',
                'transactions'
            ));
        } catch (\Exception $e) {
            \Log::error('Medicine Transactions Print Error: ' . $e->getMessage());
            return redirect()->route('medicine-purchases.stock-report')
                ->with('error', 'Failed to load medicine transactions: ' . $e->getMessage());
        }
    }
    // Add these methods if not exists
    public function print(MedicinePurchase $medicinePurchase)
    {
        $medicinePurchase->load('items.medicine');
        return view('medicine-purchases.print', compact('medicinePurchase'));
    }

    // Add a method for sales summary report
    public function salesSummaryReport()
    {
        try {
            // Get sales summary grouped by medicine
            $salesSummary = DB::table('medicine_sale_items as msi')
                ->join('medicine_sales as ms', 'msi.medicine_sale_id', '=', 'ms.id')
                ->join('medicines as m', 'msi.medicine_id', '=', 'm.id')
                ->select(
                    'msi.medicine_id',
                    'm.name',
                    'm.category',
                    DB::raw('SUM(msi.quantity) as total_sold'),
                    DB::raw('SUM(msi.final_amount) as total_sales_amount'),
                    DB::raw('AVG(msi.selling_price) as avg_selling_price'),
                    DB::raw('COUNT(DISTINCT ms.id) as sale_count')
                )
                ->where('ms.type', 'customer') // Only customer sales
                ->groupBy('msi.medicine_id', 'm.name', 'm.category')
                ->orderBy('total_sold', 'desc')
                ->get();

            // Get total sales statistics
            $totalSalesAmount = $salesSummary->sum('total_sales_amount');
            $totalItemsSold = $salesSummary->sum('total_sold');
            $totalTransactions = DB::table('medicine_sales')->where('type', 'customer')->count();

            return view('medicine-sales.summary-report', compact(
                'salesSummary',
                'totalSalesAmount',
                'totalItemsSold',
                'totalTransactions'
            ));
        } catch (\Exception $e) {
            \Log::error('Sales Summary Report Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate sales summary: ' . $e->getMessage());
        }
    }
}
