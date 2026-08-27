<?php

namespace App\Http\Controllers;

use App\Models\OpRegister;
use App\Models\InpatientRegister;
use App\Models\IpLabTest;
use App\Models\Medicine;
use App\Models\OpMedicine;
use App\Models\IpMedicine;
use App\Models\IpRadiology;
use App\Models\OpLabTest;
use App\Models\OpRadiology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    /**
     * Display pharmacy list (OP + IP)
     */
    public function index()
    {
        $today = today()->format('Y-m-d');
        // Get OP Registers for today with medicines, lab tests, and radiologies
        $opRegisters = OpRegister::with(['patient', 'medicines.medicine', 'labTests.labTest', 'radiologies.radiologyTest'])
            ->where('status', 'active')
            ->whereDate('date', today())
            ->where(function ($query) {
                $query->whereHas('medicines')
                    ->orWhereHas('labTests')
                    ->orWhereHas('radiologies');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get IP Registers with medicines (admitted patients)
        $ipRegisters = InpatientRegister::with(['patient'])
            ->whereNull('date_of_discharge')
            ->where(function ($query) use ($today) {
                $query->whereHas('medicines', function ($q) use ($today) {
                    $q->whereDate('created_at', $today);
                })
                    ->orWhereHas('ipLabTests', function ($q) use ($today) {
                        $q->whereDate('created_at', $today);
                    })
                    ->orWhereHas('ipRadiologies', function ($q) use ($today) {
                        $q->whereDate('created_at', $today);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();


        return view('pharmacy.index', compact('opRegisters', 'ipRegisters'));
    }

    /**
     * Show OP details for pharmacy
     */
    public function showOp(OpRegister $opRegister)
    {
        $opRegister->load([
            'patient',
            'medicines.medicine',
            'medicalOfficer',
            'labTests.labTest',
            'radiologies.radiologyTest'
        ]);

        $medicines = Medicine::where('status', 'active')->orderBy('name')->get();

        // Calculate totals
        $labTotal = $opRegister->labTests->sum('price');
        $radiologyTotal = $opRegister->radiologies->sum('price');
        $doctorFees = $opRegister->medicalOfficer->consulting_fee ?? 0;
        $injectionFees = $opRegister->injection_fees ?? 0;
        $procedureAmount = $opRegister->procedure_amount ?? 0; // Added procedure amount

        return view('pharmacy.op.show', compact(
            'opRegister',
            'medicines',
            'doctorFees',
            'labTotal',
            'radiologyTotal',
            'injectionFees',
            'procedureAmount' // Pass procedure amount to view
        ));
    }

    /**
     * Show IP details for pharmacy
     */


    /**
     * Issue medicines and collect lab/radiology payments for OP
     */
    public function issueOp(Request $request, OpRegister $opRegister)
{
    try {
        $validated = $request->validate([
            'doctor_fees' => 'required|numeric|min:0',
            'injection_fees' => 'nullable|numeric|min:0',
            'procedure_amount' => 'nullable|numeric|min:0', // Added procedure amount
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'paid_amount' => 'required|numeric|min:0',
            'paid_status' => 'required|in:pending,partial,paid',
            'payment_type' => 'required|in:cash,card,gpay',
            'payment_reference' => 'nullable|string|max:255',
            'overall_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'overall_discount_amount' => 'nullable|numeric|min:0',
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.morning' => 'sometimes|boolean',
            'medicines.*.afternoon' => 'sometimes|boolean',
            'medicines.*.night' => 'sometimes|boolean',
            'medicines.*.no_of_days' => 'nullable|integer',
            'medicines.*.quantity' => 'nullable|integer',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.status' => 'nullable|in:active,inactive',
            'medicines.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'medicines.*.discount_amount' => 'nullable|numeric|min:0',
            'medicines.*.instructions' => 'nullable|string',

            // Lab tests payment
            'lab_tests' => 'nullable|array',
            'lab_tests.*.id' => 'required|exists:op_lab_tests,id',
            'lab_tests.*.paid_amount' => 'required|numeric|min:0',

            // Radiology payments
            'radiologies' => 'nullable|array',
            'radiologies.*.id' => 'required|exists:op_radiologies,id',
            'radiologies.*.paid_amount' => 'required|numeric|min:0',

            // Removed tests
            'removed_lab_tests' => 'nullable|string',
            'removed_radiologies' => 'nullable|string',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed in issueOp', [
            'errors' => $e->errors(),
            'input' => $request->except(['password', 'token'])
        ]);

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    try {
        DB::transaction(function () use ($opRegister, $request) {
            $pharmacyAmount = 0;
            $labTotalAmount = 0;
            $radiologyTotalAmount = 0;
            $totalDiscount = 0;

            // Get fees and percentages
            $injectionFees = $request->injection_fees ?? 0;
            $procedureAmount = $request->procedure_amount ?? 0; // Added procedure amount
            $gstPercentage = $request->gst_percentage ?? 5;

            \Log::info('Starting issueOp transaction', [
                'op_register_id' => $opRegister->id,
                'user_id' => auth()->id(),
                'medicines_count' => count($request->medicines),
                'injection_fees' => $injectionFees,
                'procedure_amount' => $procedureAmount,
                'gst_percentage' => $gstPercentage
            ]);

            try {
                // STEP 1: Handle removed lab tests (update status to inactive)
                if ($request->has('removed_lab_tests')) {
                    $removedLabTests = json_decode($request->removed_lab_tests, true);
                    if (!empty($removedLabTests)) {
                        foreach ($removedLabTests as $testId) {
                            $labTest = OpLabTest::find($testId);
                            if ($labTest && $labTest->status != 'paid') {
                                $labTest->update(['status' => 'inactive']);
                                \Log::info('Lab test marked as inactive', [
                                    'test_id' => $testId,
                                    'op_register_id' => $opRegister->id
                                ]);
                            }
                        }
                    }
                }

                // STEP 2: Handle removed radiology tests (update status to inactive)
                if ($request->has('removed_radiologies')) {
                    $removedRadiologies = json_decode($request->removed_radiologies, true);
                    if (!empty($removedRadiologies)) {
                        foreach ($removedRadiologies as $testId) {
                            $radiology = OpRadiology::find($testId);
                            if ($radiology && $radiology->status != 'paid') {
                                $radiology->update(['status' => 'inactive']);
                                \Log::info('Radiology test marked as inactive', [
                                    'test_id' => $testId,
                                    'op_register_id' => $opRegister->id
                                ]);
                            }
                        }
                    }
                }

                // STEP 3: Delete existing medicines and restore stock if status is active
                $existingMedicines = OpMedicine::where('op_register_id', $opRegister->id)->get();

                foreach ($existingMedicines as $existingMedicine) {
                    try {
                        if ($existingMedicine->status === 'active') {
                            $stockMedicine = Medicine::find($existingMedicine->medicine_id);
                            if ($stockMedicine && $existingMedicine->quantity > 0) {
                                $stockMedicine->restoreStock($existingMedicine->quantity);
                            }
                        }
                        $existingMedicine->delete();
                    } catch (\Exception $e) {
                        \Log::error('Error processing existing medicine deletion', [
                            'medicine_id' => $existingMedicine->id,
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }

                // STEP 4: Insert new medicines fresh and reduce stock
                foreach ($request->medicines as $index => $medicineData) {
                    try {
                        $rowTotal = $medicineData['quantity'] * $medicineData['price'];
                        $rowDiscount = $medicineData['discount_amount'] ?? 0;
                        $rowNetAmount = $rowTotal - $rowDiscount;

                        $pharmacyAmount += $rowNetAmount;
                        $totalDiscount += $rowDiscount;

                        $status = isset($medicineData['status']) && $medicineData['status'] === 'active' ? 'active' : 'inactive';

                        if ($status === 'active' && $medicineData['quantity'] > 0) {
                            $stockMedicine = Medicine::find($medicineData['medicine_id']);
                            if (!$stockMedicine) {
                                throw new \Exception("Medicine not found with ID: {$medicineData['medicine_id']}");
                            }

                            if (!$stockMedicine->hasSufficientStock($medicineData['quantity'])) {
                                throw new \Exception("Insufficient stock for {$stockMedicine->name}. Available: {$stockMedicine->stock}, Required: {$medicineData['quantity']}");
                            }
                        }

                        $medicine = OpMedicine::create([
                            'op_register_id' => $opRegister->id,
                            'medicine_id' => $medicineData['medicine_id'],
                            'morning' => $medicineData['morning'] ?? 0,
                            'afternoon' => $medicineData['afternoon'] ?? 0,
                            'night' => $medicineData['night'] ?? 0,
                            'sos' => $medicineData['sos'] ?? 0,
                            'ml' => $medicineData['ml'] ?? 0,
                            'im_route' => $medicineData['im_route'] ?? 0,
                            'iv_route' => $medicineData['iv_route'] ?? 0,
                            'id_route' => $medicineData['id_route'] ?? 0,
                            'sub_q_route' => $medicineData['sub_q_route'] ?? 0,
                            'no_of_days' => $medicineData['no_of_days'],
                            'quantity' => $medicineData['quantity'],
                            'price' => $medicineData['price'],
                            'instructions' => $medicineData['instructions'] ?? null,
                            'status' => $status,
                            'discount_percentage' => $medicineData['discount_percentage'] ?? 0,
                            'discount_amount' => $medicineData['discount_amount'] ?? 0,
                            'issued_at' => now(),
                            'issued_by' => auth()->id(),
                        ]);

                        if ($status === 'active' && $medicineData['quantity'] > 0) {
                            $stockMedicine = Medicine::find($medicineData['medicine_id']);
                            $stockMedicine->reduceStock($medicineData['quantity']);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error processing medicine at index ' . $index, [
                            'medicine_data' => $medicineData,
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }

                // Process lab tests payments and update status
                if (!empty($request->lab_tests)) {
                    foreach ($request->lab_tests as $labTestData) {
                        $labTest = OpLabTest::find($labTestData['id']);
                        if ($labTest && $labTest->status != 'paid') {
                            $paidAmount = $labTestData['paid_amount'];
                            $status = $paidAmount >= $labTest->price ? 'paid' : 'partial';

                            $labTest->update([
                                'paid_amount' => $paidAmount,
                                'status' => $status,
                                'completed_at' => $status == 'paid' ? now() : null,
                            ]);
                            $labTotalAmount += $paidAmount;
                        }
                    }
                }

                // Process radiology payments and update status
                if (!empty($request->radiologies)) {
                    foreach ($request->radiologies as $radiologyData) {
                        $radiology = OpRadiology::find($radiologyData['id']);
                        if ($radiology && $radiology->status != 'paid') {
                            $paidAmount = $radiologyData['paid_amount'];
                            $status = $paidAmount >= $radiology->price ? 'paid' : 'partial';

                            $radiology->update([
                                'paid_amount' => $paidAmount,
                                'status' => $status,
                                'completed_at' => $status == 'paid' ? now() : null,
                            ]);
                            $radiologyTotalAmount += $paidAmount;
                        }
                    }
                }

                // Apply overall discount
                $overallDiscount = $request->overall_discount_amount ?? 0;
                $totalDiscount += $overallDiscount;

                // Calculate subtotal after overall discount
                $subtotal = $pharmacyAmount + $labTotalAmount + $radiologyTotalAmount + $request->doctor_fees + $injectionFees + $procedureAmount;
                $subtotalAfterDiscount = $subtotal - $overallDiscount;

                // Calculate GST
                $gstAmount = $request->gst_amount??0;

                // Calculate final total with GST
                $total = $subtotalAfterDiscount + $gstAmount;
                $paidAmount = $request->paid_amount;

                // Update OP register with all fields including procedure amount
                $updateData = [
                    'doctor_fees' => $request->doctor_fees,
                    'injection_fees' => $injectionFees,
                    'procedure_amount' => $procedureAmount, // Added procedure amount
                    'gst_percentage' => $gstPercentage,
                    'gst_amount' => $gstAmount,
                    'pharmacy_amount' => $pharmacyAmount,
                    'lab_total_amount' => $labTotalAmount,
                    'radiology_total_amount' => $radiologyTotalAmount,
                    'total_discount' => $totalDiscount,
                    'total' => $total,
                    'paid_status' => $request->paid_status,
                    'paid_amount' => $paidAmount,
                    'payment_type' => $request->payment_type,
                    'payment_reference' => $request->payment_reference,
                    'overall_discount_percentage' => $request->overall_discount_percentage ?? 0,
                    'overall_discount_amount' => $overallDiscount,
                    'pharmacy_issued_at' => now(),
                    'paid_at' => $request->paid_status == 'paid' ? now() : null,
                ];

                $opRegister->update($updateData);

                \Log::info('Successfully completed issueOp transaction', [
                    'op_register_id' => $opRegister->id,
                    'subtotal' => $subtotal,
                    'overall_discount' => $overallDiscount,
                    'subtotal_after_discount' => $subtotalAfterDiscount,
                    'gst_percentage' => $gstPercentage,
                    'gst_amount' => $gstAmount,
                    'total' => $total,
                    'paid_amount' => $paidAmount
                ]);
            } catch (\Exception $e) {
                \Log::error('Error in issueOp transaction', [
                    'op_register_id' => $opRegister->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });

        return redirect()->route('pharmacy.index')
            ->with('success', 'OP medicines issued and payments collected successfully.');
    } catch (\Exception $e) {
        \Log::error('General error in issueOp', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'An error occurred while processing your request: ' . $e->getMessage())
            ->withInput();
    }
}


    /**
     * Show OP Bill
     */
    public function billOp(OpRegister $opRegister)
    {
        $medicineTotal = $opRegister->medicines->sum(function ($medicine) {
            return ($medicine->quantity * $medicine->price) - ($medicine->discount_amount ?? 0);
        });

        $labTotal = $opRegister->labTests->sum('paid_amount');
        $radiologyTotal = $opRegister->radiologies->sum('paid_amount');
        $doctorFees = $opRegister->medicalOfficer->consulting_fee ?? 0;
        $injectionAmount = $opRegister->injection_fees ?? 0;
        $gstAmount = $opRegister->gst_amount ?? 0;
        $grandTotal = $medicineTotal + $labTotal + $radiologyTotal + $doctorFees + $gstAmount + $injectionAmount - ($opRegister->overall_discount_amount ?? 0);

        return view('pharmacy.op.bill', compact(
            'opRegister',
            'medicineTotal',
            'labTotal',
            'radiologyTotal',
            'doctorFees',
            'injectionAmount',
            'grandTotal'
        ));
    }

    /**
     * Show IP details for pharmacy - UPDATED for today's items only
     */
    public function showIp(InpatientRegister $inpatientRegister)
    {
        // Get today's date
        $today = today();

        // Load patient
        $inpatientRegister->load(['patient']);

        // Get today's medicines only (not issued)
        $todayMedicines = $inpatientRegister->medicines()
            ->whereDate('created_at', $today)
            ->whereNull('issued_at') // Only show not issued medicines
            ->with('medicine')
            ->get();

        // Get today's lab tests only (not issued)
        $todayLabTests = $inpatientRegister->ipLabTests()
            ->whereDate('created_at', $today)
            ->whereNull('issued_at') // Only show not issued lab tests
            ->with('labTest')
            ->get();

        // Get today's radiology tests only (not issued)
        $todayRadiologyTests = $inpatientRegister->ipRadiologies()
            ->whereDate('created_at', $today)
            ->whereNull('issued_at') // Only show not issued radiology tests
            ->with('radiologyTest')
            ->get();

        $medicines = Medicine::where('status', 'active')->orderBy('name')->get();

        return view('pharmacy.ip.show', compact(
            'inpatientRegister',
            'medicines',
            'todayMedicines',
            'todayLabTests',
            'todayRadiologyTests'
        ));
    }

    /**
     * Issue medicines and collect lab/radiology payments for IP - UPDATED
     */
    public function issueIp(Request $request, InpatientRegister $inpatientRegister)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'paid_status' => 'required|in:pending,partial,paid',
            'payment_type' => 'required|in:cash,card,gpay',
            'payment_reference' => 'nullable|string|max:255',
            'overall_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'overall_discount_amount' => 'nullable|numeric|min:0',
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.morning' => 'sometimes|required_without_all:medicines.*.afternoon,medicines.*.night',
            'medicines.*.afternoon' => 'sometimes|required_without_all:medicines.*.morning,medicines.*.night',
            'medicines.*.night' => 'sometimes|required_without_all:medicines.*.morning,medicines.*.afternoon',
            'medicines.*.no_of_days' => 'required|integer|min:1',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.status' => 'nullable|in:active,inactive',
            'medicines.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'medicines.*.discount_amount' => 'nullable|numeric|min:0',
            'medicines.*.instructions' => 'nullable|string',

            // Lab tests payment
            'lab_tests' => 'nullable|array',
            'lab_tests.*.id' => 'required|exists:ip_lab_tests,id',
            'lab_tests.*.paid_amount' => 'required|numeric|min:0',

            // Radiology payments
            'radiologies' => 'nullable|array',
            'radiologies.*.id' => 'required|exists:ip_radiologies,id',
            'radiologies.*.paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($inpatientRegister, $request) {
                $today = today();
                $pharmacyAmount = 0;
                $labTotalAmount = 0;
                $radiologyTotalAmount = 0;
                $totalDiscount = 0;

                // Process today's medicines
                foreach ($request->medicines as $medicineData) {
                    // Calculate row total
                    $rowTotal = $medicineData['quantity'] * $medicineData['price'];
                    $rowDiscount = $medicineData['discount_amount'] ?? 0;
                    $rowNetAmount = $rowTotal - $rowDiscount;

                    $pharmacyAmount += $rowNetAmount;
                    $totalDiscount += $rowDiscount;

                    // Update only today's medicines that are not issued
                    $medicine = IpMedicine::where('id', $medicineData['id'] ?? null)
                        ->where('inpatient_register_id', $inpatientRegister->id)
                        ->whereDate('created_at', $today)
                        ->whereNull('issued_at')
                        ->first();

                    if ($medicine) {
                        $medicine->update([
                            'medicine_id' => $medicineData['medicine_id'],
                            'morning' => $medicineData['morning'] ?? 0,
                            'afternoon' => $medicineData['afternoon'] ?? 0,
                            'night' => $medicineData['night'] ?? 0,
                            'no_of_days' => $medicineData['no_of_days'],
                            'quantity' => $medicineData['quantity'],
                            'price' => $medicineData['price'],
                            'instructions' => $medicineData['instructions'] ?? null,
                            'status' => isset($medicineData['status']) ? 'active' : 'inactive',
                            'discount_percentage' => $medicineData['discount_percentage'] ?? 0,
                            'discount_amount' => $medicineData['discount_amount'] ?? 0,
                            'issued_at' => now(),
                            'issued_by' => auth()->id(),
                            'paid_amount' => $rowNetAmount, // Set paid amount
                        ]);

                        // Reduce stock
                        $stockMedicine = Medicine::find($medicineData['medicine_id']);
                        if ($stockMedicine && $stockMedicine->hasSufficientStock($medicineData['quantity'])) {
                            $stockMedicine->reduceStock($medicineData['quantity']);
                        } else {
                            throw new \Exception("Insufficient stock for {$stockMedicine->name}. Available: {$stockMedicine->stock}, Required: {$medicineData['quantity']}");
                        }
                    }
                }

                // Process today's IP lab tests payments (not issued)
                if (!empty($request->lab_tests)) {
                    foreach ($request->lab_tests as $labTestData) {
                        $labTest = IpLabTest::where('id', $labTestData['id'])
                            ->where('inpatient_register_id', $inpatientRegister->id)
                            ->whereDate('created_at', $today)
                            ->whereNull('issued_at')
                            ->first();

                        if ($labTest) {
                            $labTest->update([
                                'paid_amount' => $labTestData['paid_amount'],
                                'issued_at' => now(),
                                'issued_by' => auth()->id(),
                                'status' => $labTestData['paid_amount'] >= $labTest->price ? 'completed' : 'partial',
                                'completed_at' => $labTestData['paid_amount'] >= $labTest->price ? now() : null,
                            ]);
                            $labTotalAmount += $labTestData['paid_amount'];
                        }
                    }
                }

                // Process today's IP radiology payments (not issued)
                if (!empty($request->radiologies)) {
                    foreach ($request->radiologies as $radiologyData) {
                        $radiology = IpRadiology::where('id', $radiologyData['id'])
                            ->where('inpatient_register_id', $inpatientRegister->id)
                            ->whereDate('created_at', $today)
                            ->whereNull('issued_at')
                            ->first();

                        if ($radiology) {
                            $radiology->update([
                                'paid_amount' => $radiologyData['paid_amount'],
                                'issued_at' => now(),
                                'issued_by' => auth()->id(),
                                'status' => $radiologyData['paid_amount'] >= $radiology->price ? 'completed' : 'partial',
                                'completed_at' => $radiologyData['paid_amount'] >= $radiology->price ? now() : null,
                            ]);
                            $radiologyTotalAmount += $radiologyData['paid_amount'];
                        }
                    }
                }

                // Apply overall discount (for today's transactions)
                $overallDiscount = $request->overall_discount_amount ?? 0;
                $totalDiscount += $overallDiscount;
                $pharmacyAmount -= $overallDiscount;

                // Calculate today's total
                $todayTotal = $pharmacyAmount + $labTotalAmount + $radiologyTotalAmount;

                // Get all existing totals from the inpatient register
                $existingPharmacyAmount = $inpatientRegister->pharmacy_amount ?? 0;
                $existingLabTotal = $inpatientRegister->lab_total_amount ?? 0;
                $existingRadiologyTotal = $inpatientRegister->radiology_total_amount ?? 0;
                $existingTotalDiscount = $inpatientRegister->total_discount ?? 0;
                $existingTotal = $inpatientRegister->total ?? 0;
                $existingPaidAmount = $inpatientRegister->paid_amount ?? 0;

                // Calculate new cumulative totals
                $newPharmacyAmount = $existingPharmacyAmount + $pharmacyAmount;
                $newLabTotalAmount = $existingLabTotal + $labTotalAmount;
                $newRadiologyTotalAmount = $existingRadiologyTotal + $radiologyTotalAmount;
                $newTotalDiscount = $existingTotalDiscount + $totalDiscount;
                $newTotal = $existingTotal + $todayTotal;
                $newPaidAmount = $existingPaidAmount + $request->paid_amount;

                // Update IP register with cumulative totals
                $inpatientRegister->update([
                    'paid_amount' => $newPaidAmount,
                    'payment_type' => $request->payment_type,
                    'payment_reference' => $request->payment_reference,
                    'overall_discount_percentage' => $request->overall_discount_percentage ?? 0,
                    'overall_discount_amount' => $overallDiscount,
                    'pharmacy_amount' => $newPharmacyAmount,
                    'lab_total_amount' => $newLabTotalAmount,
                    'radiology_total_amount' => $newRadiologyTotalAmount,
                    'total_discount' => $newTotalDiscount,
                    'total' => $newTotal,
                    'paid_status' => $request->paid_status,
                    'paid_at' => $request->paid_status == 'paid' ? now() : null,
                ]);
            });

            return redirect()->route('pharmacy.index')
                ->with('success', 'Today\'s IP medicines issued and payments collected successfully. Totals updated in register.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show IP Bill - UPDATED
     */
    public function billIp(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load(['medicines.medicine', 'ipLabTests.labTest', 'ipRadiologies.radiologyTest']);

        $medicineTotal = $inpatientRegister->medicines->sum(function ($medicine) {
            return ($medicine->quantity * $medicine->price) - ($medicine->discount_amount ?? 0);
        });

        $labTotal = $inpatientRegister->ipLabTests->sum('paid_amount');
        $radiologyTotal = $inpatientRegister->ipRadiologies->sum('paid_amount');
        $overallDiscount = $inpatientRegister->overall_discount_amount ?? 0;

        $grandTotal = $medicineTotal + $labTotal + $radiologyTotal - $overallDiscount;

        return view('pharmacy.ip.bill', compact(
            'inpatientRegister',
            'medicineTotal',
            'labTotal',
            'radiologyTotal',
            'overallDiscount',
            'grandTotal'
        ));
    }


    /**
     * Show medicine stock
     */
    public function stock()
    {
        $medicines = Medicine::orderBy('name')->get();
        return view('pharmacy.stock', compact('medicines'));
    }

    /**
     * Update medicine stock
     */
    public function updateStock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $medicine->update([
            'stock' => $request->stock
        ]);

        return redirect()->route('pharmacy.stock')
            ->with('success', 'Stock updated successfully.');
    }

    // Add these methods to your PharmacyController class

    /**
     * OP Pharmacy Report
     */
    public function opReport(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-m-01')); // Default: 1st of current month
        $toDate = $request->get('to_date', date('Y-m-d')); // Default: today

        // Query OP registers with filters
        $query = OpRegister::with(['patient', 'medicines.medicine', 'labTests.labTest', 'radiologies.radiologyTest', 'medicalOfficer'])
            ->where('status', 'active')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->where(function ($query) {
                $query->whereHas('medicines')
                    ->orWhereHas('labTests')
                    ->orWhereHas('radiologies');
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $opRegisters = $query->get();

        // Calculate totals
        $totalMedicineAmount = 0;
        $totalLabAmount = 0;
        $totalRadiologyAmount = 0;
        $totalDiscount = 0;
        $totalPaid = 0;
        $totalBalance = 0;

        foreach ($opRegisters as $op) {
            $medicineTotal = $op->medicines->sum(function ($m) {
                return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
            });
            $labTotal = $op->labTests->sum('paid_amount');
            $radiologyTotal = $op->radiologies->sum('paid_amount');
            $doctorFees = $op->medicalOfficer->consulting_fee ?? 0;
            $grandTotal = $medicineTotal + $labTotal + $radiologyTotal + $doctorFees;

            $totalMedicineAmount += $medicineTotal;
            $totalLabAmount += $labTotal;
            $totalRadiologyAmount += $radiologyTotal;
            $totalDiscount += $op->overall_discount_amount ?? 0;
            $totalPaid += $op->paid_amount ?? 0;
            $totalBalance += ($grandTotal - ($op->paid_amount ?? 0));
        }

        $grandTotalAmount = $totalMedicineAmount + $totalLabAmount + $totalRadiologyAmount - $totalDiscount;

        return view('pharmacy.reports.op', compact(
            'opRegisters',
            'fromDate',
            'toDate',
            'totalMedicineAmount',
            'totalLabAmount',
            'totalRadiologyAmount',
            'totalDiscount',
            'totalPaid',
            'totalBalance',
            'grandTotalAmount'
        ));
    }

    public function opReportPrint(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));

        // Get OP registers with calculations
        $data = $this->getOpReportData($fromDate, $toDate);

        return view('pharmacy.reports.op-print', array_merge($data, [
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]));
    }

    /**
     * Get OP Report Data (Shared function)
     */
    private function getOpReportData($fromDate, $toDate)
    {
        // Get OP registers
        $opRegisters = OpRegister::with(['patient', 'medicines', 'labTests', 'radiologies', 'medicalOfficer'])
            ->whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'desc')
            ->orderBy('token_number', 'desc')
            ->get();

        // Calculate totals
        $totalMedicineAmount = 0;
        $totalLabAmount = 0;
        $totalRadiologyAmount = 0;
        $totalDiscount = 0;
        $grandTotalAmount = 0;
        $totalPaid = 0;
        $totalBalance = 0;

        foreach ($opRegisters as $op) {
            $medicineTotal = $op->medicines->sum(function ($m) {
                return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
            });

            $labTotal = $op->labTests->sum('paid_amount');
            $radiologyTotal = $op->radiologies->sum('paid_amount');
            $doctorFees = $op->medicalOfficer->consulting_fee ?? 0;
            $discount = $op->overall_discount_amount ?? 0;
            $grandTotal = $medicineTotal + $labTotal + $radiologyTotal + $doctorFees - $discount;
            $paid = $op->paid_amount ?? 0;
            $balance = $grandTotal - $paid;

            $totalMedicineAmount += $medicineTotal;
            $totalLabAmount += $labTotal;
            $totalRadiologyAmount += $radiologyTotal;
            $totalDiscount += $discount;
            $grandTotalAmount += $grandTotal;
            $totalPaid += $paid;
            $totalBalance += $balance;
        }

        return [
            'opRegisters' => $opRegisters,
            'totalMedicineAmount' => $totalMedicineAmount,
            'totalLabAmount' => $totalLabAmount,
            'totalRadiologyAmount' => $totalRadiologyAmount,
            'totalDiscount' => $totalDiscount,
            'grandTotalAmount' => $grandTotalAmount,
            'totalPaid' => $totalPaid,
            'totalBalance' => $totalBalance
        ];
    }

    /**
     * IP Pharmacy Report
     */
    public function ipReport(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-m-01')); // Default: 1st of current month
        $toDate = $request->get('to_date', date('Y-m-d')); // Default: today

        // Query IP registers with filters
        $query = InpatientRegister::with(['patient', 'medicines.medicine', 'ipLabTests.labTest', 'ipRadiologies.radiologyTest'])
            ->whereDate('date_of_admission', '>=', $fromDate)
            ->whereDate('date_of_admission', '<=', $toDate)
            ->where(function ($query) {
                $query->whereHas('medicines')
                    ->orWhereHas('ipLabTests')
                    ->orWhereHas('ipRadiologies');
            })
            ->orderBy('date_of_admission', 'desc')
            ->orderBy('created_at', 'desc');

        $ipRegisters = $query->get();

        // Calculate totals
        $totalMedicineAmount = 0;
        $totalLabAmount = 0;
        $totalRadiologyAmount = 0;
        $totalDiscount = 0;
        $totalPaid = 0;
        $totalBalance = 0;

        foreach ($ipRegisters as $ip) {
            $medicineTotal = $ip->medicines->sum(function ($m) {
                return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
            });
            $labTotal = $ip->ipLabTests->sum('paid_amount');
            $radiologyTotal = $ip->ipRadiologies->sum('paid_amount');
            $overallDiscount = $ip->overall_discount_amount ?? 0;
            $grandTotal = $medicineTotal + $labTotal + $radiologyTotal - $overallDiscount;

            $totalMedicineAmount += $medicineTotal;
            $totalLabAmount += $labTotal;
            $totalRadiologyAmount += $radiologyTotal;
            $totalDiscount += $overallDiscount;
            $totalPaid += $ip->paid_amount ?? 0;
            $totalBalance += ($grandTotal - ($ip->paid_amount ?? 0));
        }

        $grandTotalAmount = $totalMedicineAmount + $totalLabAmount + $totalRadiologyAmount - $totalDiscount;

        return view('pharmacy.reports.ip', compact(
            'ipRegisters',
            'fromDate',
            'toDate',
            'totalMedicineAmount',
            'totalLabAmount',
            'totalRadiologyAmount',
            'totalDiscount',
            'totalPaid',
            'totalBalance',
            'grandTotalAmount'
        ));
    }

    public function ipReportPrint(Request $request)
    {
        $fromDate = $request->get('from_date', date('Y-m-01'));
        $toDate = $request->get('to_date', date('Y-m-d'));

        // Get IP registers with calculations
        $data = $this->getIpReportData($fromDate, $toDate);

        return view('pharmacy.reports.ip-print', array_merge($data, [
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]));
    }

    /**
     * Get IP Report Data (Shared function)
     */
    private function getIpReportData($fromDate, $toDate)
    {
        // Query IP registers with filters
        $query = InpatientRegister::with(['patient', 'medicines.medicine', 'ipLabTests.labTest', 'ipRadiologies.radiologyTest'])
            ->whereDate('date_of_admission', '>=', $fromDate)
            ->whereDate('date_of_admission', '<=', $toDate)
            ->where(function ($query) {
                $query->whereHas('medicines')
                    ->orWhereHas('ipLabTests')
                    ->orWhereHas('ipRadiologies');
            })
            ->orderBy('date_of_admission', 'desc')
            ->orderBy('created_at', 'desc');

        $ipRegisters = $query->get();

        // Calculate totals
        $totalMedicineAmount = 0;
        $totalLabAmount = 0;
        $totalRadiologyAmount = 0;
        $totalDiscount = 0;
        $totalPaid = 0;
        $totalBalance = 0;

        foreach ($ipRegisters as $ip) {
            $medicineTotal = $ip->medicines->sum(function ($m) {
                return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
            });
            $labTotal = $ip->ipLabTests->sum('paid_amount');
            $radiologyTotal = $ip->ipRadiologies->sum('paid_amount');
            $overallDiscount = $ip->overall_discount_amount ?? 0;
            $grandTotal = $medicineTotal + $labTotal + $radiologyTotal - $overallDiscount;

            $totalMedicineAmount += $medicineTotal;
            $totalLabAmount += $labTotal;
            $totalRadiologyAmount += $radiologyTotal;
            $totalDiscount += $overallDiscount;
            $totalPaid += $ip->paid_amount ?? 0;
            $totalBalance += ($grandTotal - ($ip->paid_amount ?? 0));
        }

        $grandTotalAmount = $totalMedicineAmount + $totalLabAmount + $totalRadiologyAmount - $totalDiscount;

        return [
            'ipRegisters' => $ipRegisters,
            'totalMedicineAmount' => $totalMedicineAmount,
            'totalLabAmount' => $totalLabAmount,
            'totalRadiologyAmount' => $totalRadiologyAmount,
            'totalDiscount' => $totalDiscount,
            'totalPaid' => $totalPaid,
            'totalBalance' => $totalBalance,
            'grandTotalAmount' => $grandTotalAmount
        ];
    }
    /**
     * Get OP details for modal (AJAX)
     */
    public function getOpDetails(OpRegister $opRegister)
    {
        $opRegister->load([
            'patient',
            'medicines.medicine',
            'labTests.labTest',
            'radiologies.radiologyTest',
            'medicalOfficer'
        ]);

        // Calculate totals
        $medicineTotal = $opRegister->medicines->sum(function ($m) {
            return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
        });
        $labTotal = $opRegister->labTests->sum('paid_amount');
        $radiologyTotal = $opRegister->radiologies->sum('paid_amount');
        $doctorFees = $opRegister->medicalOfficer->consulting_fee ?? 0;
        $discount = $opRegister->overall_discount_amount ?? 0;
        $grandTotal = $medicineTotal + $labTotal + $radiologyTotal + $doctorFees - $discount;
        $paid = $opRegister->paid_amount ?? 0;
        $balance = $grandTotal - $paid;

        $html = view('pharmacy.partials.op-details-modal', compact(
            'opRegister',
            'medicineTotal',
            'labTotal',
            'radiologyTotal',
            'doctorFees',
            'discount',
            'grandTotal',
            'paid',
            'balance'
        ))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Get IP details for modal (AJAX)
     */
    public function getIpDetails(InpatientRegister $inpatientRegister)
    {
        $inpatientRegister->load([
            'patient',
            'medicalOfficer',
            'medicines.medicine',
            'ipLabTests.labTest', // Changed from 'test' to 'labTest' based on your OP example
            'ipRadiologies.radiologyTest' // Changed from 'test' to 'radiologyTest' based on your OP example
        ]);

        // Calculate totals
        $medicineTotal = $inpatientRegister->medicines->sum(function ($m) {
            return ($m->quantity * $m->price) - ($m->discount_amount ?? 0);
        });
        $labTotal = $inpatientRegister->ipLabTests->sum('paid_amount');
        $radiologyTotal = $inpatientRegister->ipRadiologies->sum('paid_amount');
        $discount = $inpatientRegister->overall_discount_amount ?? 0;
        $grandTotal = $medicineTotal + $labTotal + $radiologyTotal - $discount;
        $paid = $inpatientRegister->paid_amount ?? 0;
        $balance = $grandTotal - $paid;

        // Since you don't have a Payment model, we'll skip payments
        return response()->json([
            'html' => view('pharmacy.partials.ip-details-modal', [
                'ip' => $inpatientRegister,
                'medicineTotal' => $medicineTotal,
                'labTotal' => $labTotal,
                'radiologyTotal' => $radiologyTotal,
                'discount' => $discount,
                'grandTotal' => $grandTotal,
                'paid' => $paid,
                'balance' => $balance
            ])->render()
        ]);
    }
}
