<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseItem;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    // Display all medicines
    public function index()
    {
        $medicines = Medicine::with('supplier')->get();

        // Decode quotes for all medicines
        $medicines = $medicines->map(function ($medicine) {
            return StringHelper::decodeQuotesInItem($medicine, ['name', 'description']);
        });

        return view('medicines.index', compact('medicines'));
    }

    // Show create medicine form
    public function create()
    {
        $suppliers = Supplier::active()->get();
        return view('medicines.create', compact('suppliers'));
    }

    // Store new medicine
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,inactive,discontinued',
            'stock' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        $data = $request->all();

        // Encode quotes in name and description before saving to database
        $data = StringHelper::encodeQuotesInArray($data, ['name', 'description']);

        $data['user_id'] = auth()->id();

        Medicine::create($data);

        return redirect()->route('medicines.success')->with('success', 'Medicine created successfully.');
    }

    // Show edit medicine form
    public function edit(Medicine $medicine)
    {
        // Decode quotes for display in the edit form
        $medicine = StringHelper::decodeQuotesInItem($medicine, ['name', 'description']);
        $suppliers = Supplier::active()->get();

        return view('medicines.edit', compact('medicine', 'suppliers'));
    }

    // Update medicine
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,discontinued',
            'stock' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        $data = $request->all();

        // Encode quotes in name and description before updating
        $data = StringHelper::encodeQuotesInArray($data, ['name', 'description']);

        $medicine->update($data);

        return redirect()->route('medicines.success')->with('success', 'Medicine updated successfully.');
    }

    // Delete medicine
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.success')->with('success', 'Medicine deleted successfully.');
    }

    // Success page
    public function success()
    {
        return view('medicines.success');
    }

    // Get medicines by supplier
    public function bySupplier($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $medicines = Medicine::bySupplier($supplierId)->get();

        return view('medicines.by-supplier', compact('supplier', 'medicines'));
    }

    // Bulk Order - List suppliers with low stock medicines
    public function bulkOrder()
    {
        try {
            // Get all suppliers with their low stock medicines
            $suppliers = Supplier::active()
                ->with(['medicines' => function ($query) {
                    $query->active()->lowStock();
                }])
                ->whereHas('medicines', function ($query) {
                    $query->active()->lowStock();
                })
                ->orderBy('name')
                ->get()
                ->map(function ($supplier) {
                    $supplier->low_stock_count = $supplier->medicines->count();
                    $supplier->total_low_stock = $supplier->medicines->sum('stock');
                    return $supplier;
                });

            return view('medicines.bulk-order', compact('suppliers'));
        } catch (\Exception $e) {
            \Log::error('Bulk Order Error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Failed to load bulk order page: ' . $e->getMessage());
        }
    }

    // Create bulk order for a supplier
    public function createBulkOrder($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);

            // Get low stock medicines for this supplier
            $lowStockMedicines = Medicine::active()
                ->bySupplier($supplierId)
                ->lowStock()
                ->orderBy('name')
                ->get()
                ->map(function ($medicine) {
                    $medicine->decoded_name = StringHelper::decodeQuotes($medicine->name);
                    // Calculate recommended quantity (restock to 50)
                    $medicine->recommended_qty = max(50 - $medicine->stock, 10);
                    return $medicine;
                });

            if ($lowStockMedicines->isEmpty()) {
                return redirect()->route('medicines.bulk-order')
                    ->with('warning', 'No low stock medicines found for this supplier.');
            }

            // Generate invoice number for bulk order
            $invoiceNumber = MedicinePurchase::generateBulkOrderInvoiceNumber();

            return view('medicines.create-bulk-order', compact(
                'supplier',
                'lowStockMedicines',
                'invoiceNumber'
            ));
        } catch (\Exception $e) {
            \Log::error('Create Bulk Order Error: ' . $e->getMessage());
            return redirect()->route('medicines.bulk-order')
                ->with('error', 'Failed to create bulk order: ' . $e->getMessage());
        }
    }

    // Store bulk order
    public function storeBulkOrder(Request $request)
    {
        \Log::info('=== BULK ORDER STORE START ===');
        \Log::info('Raw Request Data:', $request->all());

        try {
            $validator = \Validator::make($request->all(), [
                'invoice_number' => 'required|string|unique:medicine_purchases',
                'purchase_date' => 'required|date',
                'supplier_id' => 'required|exists:suppliers,id',
                'supplier_name' => 'required|string|max:255',
                'supplier_phone' => 'nullable|string|max:20',
                'supplier_address' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.medicine_id' => 'required|exists:medicines,id',
                'items.*.quantity' => 'required|integer',
                'items.*.batch_number' => 'required|string|max:100',
                'items.*.expiry_date' => 'required|date|after:today',
                'items.*.notes' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation Errors:', $validator->errors()->toArray());
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();
            \Log::info('Validated Data Successfully');

            DB::beginTransaction();

            // Encode supplier name
            $supplierNameEncoded = StringHelper::encodeQuotes($validated['supplier_name']);

            // Create bulk order purchase record
            $bulkOrderData = [
                'type' => MedicinePurchase::TYPE_BULK_ORDER,
                'invoice_number' => $validated['invoice_number'],
                'purchase_date' => $validated['purchase_date'],
                'supplier_id' => $validated['supplier_id'],
                'supplier_name' => $supplierNameEncoded,
                'supplier_phone' => $validated['supplier_phone'] ?? null,
                'supplier_address' => $validated['supplier_address'] ?? null,
                'total_amount' => 0, // Will be calculated when billed
                'paid_amount' => 0,
                'due_amount' => 0,
                'payment_status' => 'due',
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id()
            ];

            \Log::info('Creating Bulk Order Record:', $bulkOrderData);

            $bulkOrder = MedicinePurchase::create($bulkOrderData);
            \Log::info('Bulk Order Created with ID: ' . $bulkOrder->id);

            // Create purchase items with batch and expiry and update medicine stock
            $totalItems = 0;
            foreach ($validated['items'] as $index => $itemData) {
                if (empty($itemData['quantity']) || $itemData['quantity'] <= 0) {
                    continue;
                }

                \Log::info("Creating Bulk Order Item {$index}:", $itemData);

                // Create purchase item record
                $purchaseItemData = [
                    'medicine_purchase_id' => $bulkOrder->id,
                    'medicine_id' => $itemData['medicine_id'],
                    'batch_number' => $itemData['batch_number'],
                    'expiry_date' => $itemData['expiry_date'],
                    'quantity' => $itemData['quantity'],
                    'purchase_price' => 0, // To be filled later when billed
                    'selling_price' => 0,
                    'total_amount' => 0,
                    'user_id' => auth()->id()
                ];

                $purchaseItem = MedicinePurchaseItem::create($purchaseItemData);
                \Log::info("Bulk Order Item {$index} Created with ID: " . $purchaseItem->id);

                // Update medicine stock
                $medicine = \App\Models\Medicine::find($itemData['medicine_id']);
                $oldStock = $medicine->stock;
                $medicine->stock += $itemData['quantity'];
                $medicine->save();

                \Log::info("Medicine Stock Updated for ID {$itemData['medicine_id']}: {$oldStock} -> {$medicine->stock}");

                $totalItems++;
            }

            DB::commit();
            \Log::info('=== BULK ORDER STORE COMPLETED SUCCESSFULLY ===');

            return redirect()->route('medicines.bulk-order-report')
                ->with('success', 'Bulk order created successfully. ' . $totalItems . ' medicines added and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('BULK ORDER STORE ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // Bulk Order Report Screen
    public function bulkOrderReport()
    {
        try {
            $bulkOrders = MedicinePurchase::bulkOrders()
                ->with(['supplier', 'items.medicine'])
                ->latest()
                ->paginate(20);

            // Decode supplier names for display
            $bulkOrders->getCollection()->transform(function ($order) {
                $order->supplier_name_decoded = StringHelper::decodeQuotes($order->supplier_name);
                return $order;
            });

            return view('medicines.bulk-order-report', compact('bulkOrders'));
        } catch (\Exception $e) {
            \Log::error('Bulk Order Report Error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Failed to load bulk order report: ' . $e->getMessage());
        }
    }

    // Edit Bulk Order (to add pricing and update stock)
    public function editBulkOrder($id)
    {
        try {
            $bulkOrder = MedicinePurchase::bulkOrders()
                ->with(['items.medicine'])
                ->findOrFail($id);

            // Check if already billed
            if ($bulkOrder->total_amount > 0) {
                return redirect()->route('medicines.bulk-order-report')
                    ->with('warning', 'This bulk order has already been billed.');
            }

            // Decode supplier name
            $bulkOrder->supplier_name_decoded = StringHelper::decodeQuotes($bulkOrder->supplier_name);

            // Decode medicine names
            $bulkOrder->items->each(function ($item) {
                if ($item->medicine) {
                    $item->medicine->decoded_name = StringHelper::decodeQuotes($item->medicine->name);
                }
            });

            return view('medicines.edit-bulk-order', compact('bulkOrder'));
        } catch (\Exception $e) {
            \Log::error('Edit Bulk Order Error: ' . $e->getMessage());
            return redirect()->route('medicines.bulk-order-report')
                ->with('error', 'Failed to load bulk order: ' . $e->getMessage());
        }
    }

    // Update Bulk Order (Add pricing and update stock)
    public function updateBulkOrder(Request $request, $id)
    {
        \Log::info('=== BULK ORDER UPDATE START ===');
        \Log::info('Raw Request Data:', $request->all());

        try {
            $bulkOrder = MedicinePurchase::bulkOrders()->findOrFail($id);

            // Check if already billed
            if ($bulkOrder->total_amount > 0) {
                return redirect()->route('medicines.bulk-order-report')
                    ->with('warning', 'This bulk order has already been billed and cannot be edited.');
            }

            $validator = \Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:medicine_purchase_items,id',
                'items.*.medicine_id' => 'required|exists:medicines,id',
                'items.*.quantity' => 'required|integer',
                'items.*.batch_number' => 'required|string|max:100',
                'items.*.expiry_date' => 'required|date|after:today',
                'items.*.notes' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation Errors:', $validator->errors()->toArray());
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();
            \Log::info('Validated Data Successfully');

            DB::beginTransaction();

            $totalItems = 0;
            $stockAdjustments = [];

            // First, get original quantities to calculate stock adjustments
            $originalItems = [];
            foreach ($bulkOrder->items as $item) {
                $originalItems[$item->id] = [
                    'quantity' => $item->quantity,
                    'medicine_id' => $item->medicine_id
                ];
            }

            // Update each item
            foreach ($validated['items'] as $itemData) {
                $purchaseItem = MedicinePurchaseItem::find($itemData['id']);

                if ($purchaseItem && $purchaseItem->medicine_purchase_id == $bulkOrder->id) {
                    $originalQuantity = $originalItems[$purchaseItem->id]['quantity'] ?? 0;
                    $newQuantity = $itemData['quantity'];

                    // Update purchase item
                    $purchaseItem->update([
                        'batch_number' => $itemData['batch_number'],
                        'expiry_date' => $itemData['expiry_date'],
                        'quantity' => $newQuantity,
                        'notes' => $itemData['notes'] ?? null,
                    ]);

                    // Calculate stock adjustment
                    $quantityDifference = $newQuantity - $originalQuantity;

                    if ($quantityDifference != 0) {
                        $stockAdjustments[] = [
                            'medicine_id' => $purchaseItem->medicine_id,
                            'difference' => $quantityDifference
                        ];
                    }

                    $totalItems++;
                }
            }

            // Apply stock adjustments
            foreach ($stockAdjustments as $adjustment) {
                $medicine = Medicine::find($adjustment['medicine_id']);
                if ($medicine) {
                    $oldStock = $medicine->stock;
                    $newStock = $oldStock + $adjustment['difference'];

                    // Prevent negative stock
                    if ($newStock < 0) {
                        throw new \Exception("Cannot update order. Medicine '{$medicine->name}' would have negative stock.");
                    }

                    $medicine->update(['stock' => $newStock]);
                    \Log::info("Stock adjusted for medicine ID {$medicine->id}: {$oldStock} -> {$newStock} (Change: {$adjustment['difference']})");
                }
            }

            // Update order notes
            $bulkOrder->update([
                'notes' => $validated['notes'] ?? $bulkOrder->notes,
            ]);

            DB::commit();
            \Log::info('=== BULK ORDER UPDATE COMPLETED SUCCESSFULLY ===');

            return redirect()->route('medicines.bulk-order-report')
                ->with('success', 'Bulk order updated successfully. Stock adjusted for ' . count($stockAdjustments) . ' medicines.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('BULK ORDER UPDATE ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // Print Bulk Order
    public function printBulkOrder($id)
    {
        try {
            $bulkOrder = MedicinePurchase::with(['items.medicine', 'supplier'])
                ->findOrFail($id);

            // Decode names for printing
            $bulkOrder->supplier_name_decoded = StringHelper::decodeQuotes($bulkOrder->supplier_name);
            $bulkOrder->items->each(function ($item) {
                if ($item->medicine) {
                    $item->medicine->decoded_name = StringHelper::decodeQuotes($item->medicine->name);
                }
            });

            return view('medicines.print-bulk-order', compact('bulkOrder'));
        } catch (\Exception $e) {
            \Log::error('Print Bulk Order Error: ' . $e->getMessage());
            return redirect()->route('medicines.bulk-order-report')
                ->with('error', 'Failed to generate print: ' . $e->getMessage());
        }
    }

    // Delete Bulk Order
    public function deleteBulkOrder($id)
    {
        try {
            $bulkOrder = MedicinePurchase::bulkOrders()->findOrFail($id);

            // Check if already billed
            if ($bulkOrder->total_amount > 0) {
                return redirect()->route('medicines.bulk-order-report')
                    ->with('error', 'Cannot delete billed bulk order. Please contact administrator.');
            }

            DB::beginTransaction();

            // First, reduce stock for each medicine in the bulk order
            foreach ($bulkOrder->items as $item) {
                $medicine = Medicine::find($item->medicine_id);

                if ($medicine) {
                    $oldStock = $medicine->stock;
                    $newStock = $oldStock - $item->quantity;

                    // Prevent negative stock
                    if ($newStock < 0) {
                        throw new \Exception("Cannot delete order. Medicine '{$medicine->name}' would have negative stock ({$oldStock} - {$item->quantity} = {$newStock}).");
                    }

                    $medicine->update(['stock' => $newStock]);

                    \Log::info("Stock reduced for medicine ID {$medicine->id}: {$oldStock} -> {$newStock} (Removed: {$item->quantity})");
                }
            }

            // Then delete the items and the bulk order
            $bulkOrder->items()->delete();
            $bulkOrder->delete();

            DB::commit();

            return redirect()->route('medicines.bulk-order-report')
                ->with('success', 'Bulk order deleted successfully. Stock has been adjusted for all medicines.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete Bulk Order Error: ' . $e->getMessage());

            return redirect()->route('medicines.bulk-order-report')
                ->with('error', 'Failed to delete bulk order: ' . $e->getMessage());
        }
    }
}
