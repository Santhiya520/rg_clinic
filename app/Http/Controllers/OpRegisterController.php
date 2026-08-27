<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\OpRegister;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medicine;
use App\Models\RadiologyTest;
use App\Models\LabTest;
use App\Models\OpLabSubTest;
use App\Models\OpLabTest;
use App\Models\OpMedicine;
use App\Models\OpRadiology;
use Illuminate\Http\Request;

class OpRegisterController extends Controller
{
    // Display all OP registers
    public function index()
    {
        $registers = OpRegister::with(['patient', 'medicalOfficer'])
            ->whereDate('date', today())
            ->orderBy('id', 'desc')
            ->get();

        return view('op-registers.index', compact('registers'));
    }

    // Show create OP register form (reception View)
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $doctors = User::doctors()->orderBy('name')->get();

        // Generate next OP number
        $lastOp = OpRegister::latest()->first();
        $nextOpNo = 'OP' . ($lastOp ? $lastOp->id + 1 : 1);

        return view('op-registers.create', compact('patients', 'doctors', 'nextOpNo'));
    }

    // Store new OP register (reception)
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medical_officer_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'op_no' => 'required|string|max:200|unique:op_registers,op_no',
            'weight' => 'nullable|string|max:200',
            'height' => 'nullable|string|max:200',
            'pluse' => 'nullable|string|max:200',
            'spo2' => 'nullable|string|max:200',
            'bp' => 'nullable|string|max:200',
            'temparature' => 'nullable|string|max:200',
            'comorbidities' => 'nullable|string',
            'history' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        if ($request->filled('comorbidities')) {
            $patient->comorbidities = $request->comorbidities;
        }

        if ($request->filled('history')) {
            $patient->history = $request->history;
        }

        $patient->save();

        $opRegister = OpRegister::create([
            'patient_id' => $request->patient_id,
            'medical_officer_id' => $request->medical_officer_id,
            'date' => $request->date,
            'token_number' => OpRegister::generateTokenNumber(),
            'status' => 'registered',
            'user_id' => auth()->id(),
            'op_no' => $request->op_no,
            'weight' => $request->weight,
            'height' => $request->height,
            'pluse' => $request->pluse,
            'spo2' => $request->spo2,
            'bp' => $request->bp,
            'temparature' => $request->temparature,
        ]);

        return redirect()->route('op-registers.success')->with('success', 'OP register entry created successfully. Token Number: ' . $opRegister->token_number);
    }

    // Show edit OP register form
    public function edit(OpRegister $opRegister)
    {
        $patients = Patient::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();

        return view('op-registers.edit', compact('opRegister', 'patients', 'doctors'));
    }

    // Update OP register
    public function update(Request $request, OpRegister $opRegister)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medical_officer_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'additional_information' => 'nullable|string|max:1000',
            // New fields validation
            'op_no' => 'required|string|max:200|unique:op_registers,op_no,' . $opRegister->id,
            'weight' => 'nullable|string|max:200',
            'height' => 'nullable|string|max:200',
            'pluse' => 'nullable|string|max:200',
            'spo2' => 'nullable|string|max:200',
            'bp' => 'nullable|string|max:200',
            'temparature' => 'nullable|string|max:200',
            'comorbidities' => 'nullable|string',
            'history' => 'nullable|string',
        ]);

        try {
            // Check if the medical_officer_id belongs to a doctor
            $doctor = User::where('id', $validated['medical_officer_id'])
                ->where('role', 'doctor')
                ->first();

            if (!$doctor) {
                return back()->with('error', 'Selected medical officer is not a valid doctor.')->withInput();
            }

            // Update patient's comorbidities and history if provided
            $patient = Patient::findOrFail($validated['patient_id']);

            $patientUpdates = [];

            if ($request->filled('comorbidities')) {
                $patientUpdates['comorbidities'] = $validated['comorbidities'];
            }

            if ($request->filled('history')) {
                $patientUpdates['history'] = $validated['history'];
            }

            if (!empty($patientUpdates)) {
                $patient->update($patientUpdates);
            }

            // Update the OP register
            $opRegister->update([
                'patient_id' => $validated['patient_id'],
                'medical_officer_id' => $validated['medical_officer_id'],
                'date' => $validated['date'],
                'additional_information' => $validated['additional_information'] ?? null,
                // New fields
                'op_no' => $validated['op_no'],
                'weight' => $validated['weight'],
                'height' => $validated['height'],
                'pluse' => $validated['pluse'],
                'spo2' => $validated['spo2'],
                'bp' => $validated['bp'],
                'temparature' => $validated['temparature'],
            ]);

            // Log the update
            \Log::info('OP Register updated successfully', [
                'id' => $opRegister->id,
                'updated_by' => auth()->id(),
                'changes' => $validated
            ]);

            return redirect()->route('op-registers.index')
                ->with('success', 'OP register entry updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating OP Register: ' . $e->getMessage(), [
                'op_register_id' => $opRegister->id,
                'request_data' => $validated,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update OP register entry: ' . $e->getMessage())->withInput();
        }
    }

    // Delete OP register
    public function destroy(OpRegister $opRegister)
    {
        try {
            // Only allow deletion of registered entries
            if ($opRegister->status !== 'registered') {
                return redirect()->route('op-registers.index')
                    ->with('error', 'Only registered OP entries can be deleted.');
            }

            $tokenNumber = $opRegister->token_number;
            $opRegister->delete();

            \Log::info('OP Register deleted', [
                'token_number' => $tokenNumber,
                'deleted_by' => auth()->id()
            ]);

            return redirect()->route('op-registers.index')
                ->with('success', 'OP register entry deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting OP Register: ' . $e->getMessage(), [
                'op_register_id' => $opRegister->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('op-registers.index')
                ->with('error', 'Failed to delete OP register entry: ' . $e->getMessage());
        }
    }

    // Doctor OP Screen
    public function doctorOp()
    {
        $user = auth()->user();

        $query = OpRegister::with(['patient', 'medicalOfficer'])
            ->where('status', '!=', 'completed')
            ->whereDate('date', today());

        // If role is doctor, filter by medical_officer_id
        if ($user->role === 'doctor') {
            $query->where('medical_officer_id', $user->id);
        }

        $registers = $query->orderBy('id', 'desc')->get();

        $medicines = Medicine::get();
        $radiologyTests = RadiologyTest::get();
        $labTests = LabTest::get();

        return view('op-registers.doctor-op', compact(
            'registers',
            'medicines',
            'radiologyTests',
            'labTests'
        ));
    }

    public function doctorPrint(OpRegister $opRegister)
    {
        $medicineTotal = $opRegister->medicines->sum(function ($medicine) {
            return $medicine->quantity * $medicine->price;
        });

        $doctorFees = $opRegister->medicalOfficer->consulting_fee ?? 0;
        $grandTotal = $medicineTotal + $doctorFees;

        return view('op-registers.doctor-print', compact(
            'opRegister',
            'medicineTotal',
            'doctorFees',
            'grandTotal'
        ));
    }

    public function createPrescription(OpRegister $opRegister)
    {
        $opRegister->load('patient');

        // Get only medicines with stock available (> 0)
        $medicines = Medicine::where('stock', '>', 0)->get();

        $radiologyTests = RadiologyTest::all();
        $labTests = LabTest::all();

        return view('op-registers.prescription-create', compact(
            'opRegister',
            'medicines',
            'radiologyTests',
            'labTests'
        ));
    }

    public function storePrescription(Request $request, OpRegister $register)
    {
        $validated = $request->validate([
            'op_register_id' => 'required',
            'op_no' => 'required|string|max:200',
            'weight' => 'nullable|string|max:200',
            'height' => 'nullable|string|max:200',
            'pluse' => 'nullable|string|max:200',
            'spo2' => 'nullable|string|max:200',
            'bp' => 'nullable|string|max:200',
            'temparature' => 'nullable|string|max:200',
            'comorbidities' => 'nullable|string',
            'history' => 'nullable|string',
            'provisional_diagnosis' => 'required|string',
            'investigations' => 'nullable|string',
            'final_diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'result' => 'nullable|string',
            'status' => 'required|string',
            'additional_information' => 'nullable|string',
            'medicines' => 'nullable|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.no_of_days' => 'nullable|integer|min:0',
            'medicines.*.quantity' => 'nullable|integer|min:0',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.morning' => 'nullable|boolean',
            'medicines.*.afternoon' => 'nullable|boolean',
            'medicines.*.night' => 'nullable|boolean',
            'medicines.*.sos' => 'nullable|boolean',
            'medicines.*.ml' => 'nullable|boolean',
            'medicines.*.im_route' => 'nullable|boolean',
            'medicines.*.iv_route' => 'nullable|boolean',
            'medicines.*.id_route' => 'nullable|boolean',
            'medicines.*.sub_q_route' => 'nullable|boolean',
            'medicines.*.instructions' => 'nullable|string',
            'radiologies' => 'nullable|array',
            'radiologies.*.radiology_test_id' => 'required|exists:radiology_tests,id',
            'radiologies.*.notes' => 'nullable|string',
            'lab_tests' => 'nullable|array',
            'lab_tests.*.lab_test_id' => 'required|exists:lab_tests,id',
            'lab_tests.*.notes' => 'nullable|string',
            'lab_tests.*.sub_tests' => 'nullable|array',
        ]);

        $register = OpRegister::find(intval($validated['op_register_id']));

        DB::transaction(function () use ($register, $validated) {
            // Update OP register with patient vitals and other fields
            $register->update([
                'op_no' => $validated['op_no'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'height' => $validated['height'] ?? null,
                'pluse' => $validated['pluse'] ?? null,
                'spo2' => $validated['spo2'] ?? null,
                'bp' => $validated['bp'] ?? null,
                'temparature' => $validated['temparature'] ?? null,
                'provisional_diagnosis' => $validated['provisional_diagnosis'] ?? null,
                'investigations' => $validated['investigations'] ?? null,
                'final_diagnosis' => $validated['final_diagnosis'] ?? null,
                'treatment' => $validated['treatment'] ?? null,
                'result' => $validated['result'] ?? null,
                'status' => $validated['status'] ?? null,
                'additional_information' => $validated['additional_information'] ?? null,
            ]);

            // Update patient comorbidities and history
            if ($register->patient) {
                $patientData = [];

                // Only update if data is provided (not empty or null)
                if (isset($validated['comorbidities'])) {
                    $patientData['comorbidities'] = $validated['comorbidities'];
                }

                if (isset($validated['history'])) {
                    $patientData['history'] = $validated['history'];
                }

                // Update patient if there's data to update
                if (!empty($patientData)) {
                    $register->patient->update($patientData);
                }
            }

            // Sync medicines
            if (isset($validated['medicines'])) {
                $register->medicines()->delete(); // Remove existing
                foreach ($validated['medicines'] as $medicineData) {
                    if ($medicineData['medicine_id'] > 0) {
                        $medicineDatas = [
                            'op_register_id' => intval($validated['op_register_id']),
                            'medicine_id' => $medicineData['medicine_id'] ?? 0,
                            'morning' => $medicineData['morning'] ?? 0,
                            'afternoon' => $medicineData['afternoon'] ?? 0,
                            'night' => $medicineData['night'] ?? 0,
                            'sos' => $medicineData['sos'] ?? 0,
                            'ml' => $medicineData['ml'] ?? 0,
                            'im_route' => $medicineData['im_route'] ?? 0,
                            'iv_route' => $medicineData['iv_route'] ?? 0,
                            'id_route' => $medicineData['id_route'] ?? 0,
                            'sub_q_route' => $medicineData['sub_q_route'] ?? 0,
                            'no_of_days' => $medicineData['no_of_days'] ?? 0,
                            'quantity' => $medicineData['quantity'] ?? 0,
                            'price' => $medicineData['price'] ?? 0,
                            'instructions' => $medicineData['instructions'] ?? '',
                            'user_id' => auth()->id()
                        ];

                        OpMedicine::create($medicineDatas);
                    }
                }
            }

            // Sync radiology tests
            if (isset($validated['radiologies'])) {
                OpRadiology::where('op_register_id', $validated['op_register_id'])->delete();

                foreach ($validated['radiologies'] as $radiologyData) {
                    if ($radiologyData['radiology_test_id'] > 0) {
                        $test = RadiologyTest::find($radiologyData['radiology_test_id']);

                        $radiologyDatas = [
                            'op_register_id' => $validated['op_register_id'],
                            'radiology_test_id' => $radiologyData['radiology_test_id'],
                            'price' => $test->price ?? 0,
                            'notes' => $radiologyData['notes'] ?? null,
                            'user_id' => auth()->id()
                        ];

                        OpRadiology::create($radiologyDatas);
                    }
                }
            }

            // Sync lab tests and sub tests
            if (isset($validated['lab_tests'])) {
                // Delete existing lab tests and their sub tests
                $existingLabTests = OpLabTest::where('op_register_id', $validated['op_register_id'])->get();
                foreach ($existingLabTests as $existingLabTest) {
                    OpLabSubTest::where('op_lab_test_id', $existingLabTest->id)->delete();
                    $existingLabTest->delete();
                }

                foreach ($validated['lab_tests'] as $labTestData) {
                    if ($labTestData['lab_test_id'] > 0) {
                        $test = LabTest::find($labTestData['lab_test_id']);

                        // Create main lab test
                        $opLabTest = OpLabTest::create([
                            'op_register_id' => $validated['op_register_id'],
                            'lab_test_id' => $labTestData['lab_test_id'],
                            'price' => $test->price ?? 0,
                            'notes' => $labTestData['notes'] ?? null,
                            'user_id' => auth()->id()
                        ]);

                        // Create sub tests if any are selected
                        if (isset($labTestData['sub_tests']) && is_array($labTestData['sub_tests'])) {
                            $order = 0;
                            foreach ($labTestData['sub_tests'] as $subTestId => $subTestData) {
                                // Only create if checkbox is checked
                                if (isset($subTestData['checked']) && $subTestData['checked'] == 1) {
                                    OpLabSubTest::create([
                                        'op_lab_test_id' => $opLabTest->id,
                                        'lab_sub_test_id' => $subTestData['lab_sub_test_id'] ?? $subTestId,
                                        'test_name' => $subTestData['test_name'] ?? '',
                                        'unit' => $subTestData['unit'] ?? null,
                                        'normal_range' => $subTestData['normal_range'] ?? null,
                                        'result' => null, // Will be updated later when results are entered
                                        'order' => $subTestData['order'] ?? $order,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                    $order++;
                                }
                            }
                        }
                    }
                }
            }
        });

        return redirect()->route('op-registers.doctor-op')
            ->with('success', 'Prescription added successfully');
    }

    public function editPrescription(OpRegister $opRegister)
    {
        $opRegister->load(['patient', 'medicines', 'radiologyTests', 'labTests']);
        $medicines = Medicine::where('stock', '>', 0)->get();
        $radiology = RadiologyTest::all();
        $labTests = LabTest::all();

        // dd($radiology);
        return view('op-registers.prescription-edit', compact(
            'opRegister',
            'medicines',
            'radiology',
            'labTests'
        ));
    }

    public function updatePrescription(Request $request, OpRegister $opRegister)
    {
        $validated = $request->validate([
            'op_no' => 'required|string|max:200',
            'weight' => 'nullable|string|max:200',
            'height' => 'nullable|string|max:200',
            'pluse' => 'nullable|string|max:200',
            'spo2' => 'nullable|string|max:200',
            'bp' => 'nullable|string|max:200',
            'temparature' => 'nullable|string|max:200',
            'comorbidities' => 'nullable|string',
            'history' => 'nullable|string',
            'provisional_diagnosis' => 'required|string',
            'investigations' => 'nullable|string',
            'final_diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'result' => 'nullable|string',
            'status' => 'required|string',
            'additional_information' => 'nullable|string',
            'medicines' => 'nullable|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.no_of_days' => 'nullable|integer|min:0',
            'medicines.*.quantity' => 'nullable|integer|min:0',
            'medicines.*.price' => 'required|numeric|min:0',
            'medicines.*.morning' => 'nullable|boolean',
            'medicines.*.afternoon' => 'nullable|boolean',
            'medicines.*.night' => 'nullable|boolean',
            'medicines.*.sos' => 'nullable|boolean',
            'medicines.*.ml' => 'nullable|boolean',
            'medicines.*.im_route' => 'nullable|boolean',
            'medicines.*.iv_route' => 'nullable|boolean',
            'medicines.*.id_route' => 'nullable|boolean',
            'medicines.*.sub_q_route' => 'nullable|boolean',
            'medicines.*.instructions' => 'nullable|string',
            'radiologies' => 'nullable|array',
            'radiologies.*.radiology_test_id' => 'required|exists:radiology_tests,id',
            'radiologies.*.notes' => 'nullable|string',
            'lab_tests' => 'nullable|array',
            'lab_tests.*.lab_test_id' => 'required|exists:lab_tests,id',
            'lab_tests.*.notes' => 'nullable|string',
            'lab_tests.*.sub_tests' => 'nullable|array',
        ]);

        DB::transaction(function () use ($opRegister, $validated) {
            // Update OP register with patient vitals and other fields
            $opRegister->update([
                'op_no' => $validated['op_no'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'height' => $validated['height'] ?? null,
                'pluse' => $validated['pluse'] ?? null,
                'spo2' => $validated['spo2'] ?? null,
                'bp' => $validated['bp'] ?? null,
                'temparature' => $validated['temparature'] ?? null,
                'provisional_diagnosis' => $validated['provisional_diagnosis'] ?? null,
                'investigations' => $validated['investigations'] ?? null,
                'final_diagnosis' => $validated['final_diagnosis'] ?? null,
                'treatment' => $validated['treatment'] ?? null,
                'result' => $validated['result'] ?? null,
                'status' => $validated['status'] ?? null,
                'additional_information' => $validated['additional_information'] ?? null,
            ]);

            // Update patient comorbidities and history
            if ($opRegister->patient) {
                $patientData = [];

                // Only update if data is provided (not empty or null)
                if (isset($validated['comorbidities'])) {
                    $patientData['comorbidities'] = $validated['comorbidities'];
                }

                if (isset($validated['history'])) {
                    $patientData['history'] = $validated['history'];
                }

                // Update patient if there's data to update
                if (!empty($patientData)) {
                    $opRegister->patient->update($patientData);
                }
            }

            // Update medicines
            if (isset($validated['medicines'])) {
                $opRegister->medicines()->delete(); // Remove existing medicines

                foreach ($validated['medicines'] as $medicineData) {
                    if ($medicineData['medicine_id'] > 0) {
                        OpMedicine::create([
                            'op_register_id' => $opRegister->id,
                            'medicine_id'    => $medicineData['medicine_id'],
                            'morning'        => $medicineData['morning'] ?? 0,
                            'afternoon'      => $medicineData['afternoon'] ?? 0,
                            'night'          => $medicineData['night'] ?? 0,
                            'sos'            => $medicineData['sos'] ?? 0,
                            'ml'             => $medicineData['ml'] ?? 0,
                            'im_route'       => $medicineData['im_route'] ?? 0,
                            'iv_route'       => $medicineData['iv_route'] ?? 0,
                            'id_route'       => $medicineData['id_route'] ?? 0,
                            'sub_q_route'    => $medicineData['sub_q_route'] ?? 0,
                            'no_of_days'     => $medicineData['no_of_days'] ?? 0,
                            'quantity'       => $medicineData['quantity'] ?? 0,
                            'price'          => $medicineData['price'],
                            'instructions'   => $medicineData['instructions'] ?? null,
                            'user_id'        => auth()->id()
                        ]);
                    }
                }
            } else {
                // If no medicines in request, remove all existing medicines
                $opRegister->medicines()->delete();
            }

            // Update radiology tests
            if (isset($validated['radiologies'])) {
                $opRegister->radiologyTests()->delete();

                foreach ($validated['radiologies'] as $radiologyData) {
                    if ($radiologyData['radiology_test_id'] > 0) {
                        $test = RadiologyTest::find($radiologyData['radiology_test_id']);

                        OpRadiology::create([
                            'op_register_id'     => $opRegister->id,
                            'radiology_test_id'  => $radiologyData['radiology_test_id'],
                            'price'              => $test->price ?? 0,
                            'notes'              => $radiologyData['notes'] ?? null,
                            'user_id'            => auth()->id()
                        ]);
                    }
                }
            } else {
                $opRegister->radiologyTests()->delete();
            }

            // Update lab tests and sub tests
            if (isset($validated['lab_tests'])) {
                // Delete existing lab tests and their sub tests
                $existingLabTests = OpLabTest::where('op_register_id', $opRegister->id)->get();
                foreach ($existingLabTests as $existingLabTest) {
                    OpLabSubTest::where('op_lab_test_id', $existingLabTest->id)->delete();
                    $existingLabTest->delete();
                }

                foreach ($validated['lab_tests'] as $labTestData) {
                    if ($labTestData['lab_test_id'] > 0) {
                        $test = LabTest::find($labTestData['lab_test_id']);

                        // Create main lab test
                        $opLabTest = OpLabTest::create([
                            'op_register_id' => $opRegister->id,
                            'lab_test_id'    => $labTestData['lab_test_id'],
                            'price'          => $test->price ?? 0,
                            'notes'          => $labTestData['notes'] ?? null,
                            'user_id'        => auth()->id()
                        ]);

                        // Create sub tests if any are selected
                        if (isset($labTestData['sub_tests']) && is_array($labTestData['sub_tests'])) {
                            $order = 0;
                            foreach ($labTestData['sub_tests'] as $subTestId => $subTestData) {
                                // Only create if checkbox is checked
                                if (isset($subTestData['checked']) && $subTestData['checked'] == 1) {
                                    OpLabSubTest::create([
                                        'op_lab_test_id' => $opLabTest->id,
                                        'lab_sub_test_id' => $subTestData['lab_sub_test_id'] ?? $subTestId,
                                        'test_name' => $subTestData['test_name'] ?? '',
                                        'unit' => $subTestData['unit'] ?? null,
                                        'normal_range' => $subTestData['normal_range'] ?? null,
                                        'result' => null, // Will be updated later when results are entered
                                        'order' => $subTestData['order'] ?? $order,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                    $order++;
                                }
                            }
                        }
                    }
                }
            } else {
                // If no lab tests in request, remove all existing lab tests and their sub tests
                $existingLabTests = OpLabTest::where('op_register_id', $opRegister->id)->get();
                foreach ($existingLabTests as $existingLabTest) {
                    OpLabSubTest::where('op_lab_test_id', $existingLabTest->id)->delete();
                    $existingLabTest->delete();
                }
            }
        });

        return redirect()->route('op-registers.doctor-op')
            ->with('success', 'Prescription updated successfully');
    }

    // Add this method to your OpRegisterController
    public function prescriptionView(OpRegister $opRegister)
    {
        // Load only necessary relationships with specific columns
        $opRegister->load([
            'patient:id,name,age,sex,patient_id,comorbidities,history',
            'medicines' => function ($query) {
                $query->with(['medicine:id,name,category,price']);
            },
            'radiologyTests' => function ($query) {
                $query->with(['radiologyTest:id,name,price']);
            },
            'labTests' => function ($query) {
                $query->with([
                    'labTest:id,name,price',
                    'subTests'  // Load sub tests with all columns
                ]);
            }
        ]);

        // Get only necessary data for dropdowns (if needed for edit functionality)
        $medicines = Medicine::select('id', 'name', 'category', 'price')->get();
        $radiology = RadiologyTest::select('id', 'name', 'price')->get();
        $labTests = LabTest::with('subTests')->select('id', 'name', 'price')->get();

        return view('op-registers.prescription-view', compact('opRegister', 'medicines', 'radiology', 'labTests'));
    }

    // Get OP register details for doctor
    public function getOpDetails(OpRegister $opRegister)
    {
        $opRegister->load(['patient', 'medicines.medicine', 'radiologyTests.radiologyTest', 'labTests.labTest']);

        return response()->json($opRegister);
    }


    // Success page
    public function success()
    {
        return view('op-registers.success');
    }

    // Get patient details for OP register
    public function getPatientDetails($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        return response()->json([
            'name' => $patient->name,
            'address' => $patient->address,
            'mobile' => $patient->mobile,
            'age' => $patient->age,
            'sex' => $patient->sex,
            'patient_id' => $patient->patient_id
        ]);
    }


    public function report(Request $request)
    {
        // Get all patients for dropdown
        $patients = Patient::orderBy('name')->get(['id', 'patient_id', 'name']);

        // Get all doctors for dropdown
        $doctors = User::where('role', 'doctor')
            ->orderBy('name')
            ->get(['id', 'name']);

        $query = OpRegister::with([
            'patient',
            'medicalOfficer',
            'radiologyTests.radiologyTest',
            'labTests.labTest'
        ]);

        // Apply filters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date . ' 00:00:00');
        } elseif ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Patient filter
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Doctor filter
        if ($request->filled('medical_officer_id')) {
            $query->where('medical_officer_id', $request->medical_officer_id);
        }

        // OP Number filter
        if ($request->filled('op_no')) {
            $query->where('op_no', 'like', '%' . $request->op_no . '%');
        }

        // Token Number filter
        if ($request->filled('token_number')) {
            $query->where('token_number', 'like', '%' . $request->token_number . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by creation date (most recent first)
        $query->orderBy('created_at', 'desc');

        // Get all records without pagination
        $opRegisters = $query->get();

        // Calculate totals
        $totalAmountSum = 0;
        $totalPaidSum = 0;

        foreach ($opRegisters as $opRegister) {
            $radiologyTotal = $opRegister->radiologyTests->sum('price');
            $radiologyPaid = $opRegister->radiologyTests->sum('paid_amount');
            $labTotal = $opRegister->labTests->sum('price');
            $labPaid = $opRegister->labTests->sum('paid_amount');

            $totalAmountSum += ($radiologyTotal + $labTotal);
            $totalPaidSum += ($radiologyPaid + $labPaid);
        }

        return view('op-registers.report', compact(
            'opRegisters',
            'patients',
            'doctors',
            'totalAmountSum',
            'totalPaidSum'
        ));
    }

    public function preview(OpRegister $opRegister)
    {
        // Load all relationships including medicines
        $opRegister->load([
            'patient',
            'medicalOfficer',
            'radiologyTests.radiologyTest',
            'labTests.labTest',
            'medicines.medicine' // Add this line
        ]);

        // Calculate totals
        $radiologyTotal = $opRegister->radiologyTests->sum('price');
        $radiologyPaid = $opRegister->radiologyTests->sum('paid_amount');
        $labTotal = $opRegister->labTests->sum('price');
        $labPaid = $opRegister->labTests->sum('paid_amount');

        // Calculate medicine totals
        $medicineTotal = $opRegister->medicines->sum('price');
        $medicinePaid = $opRegister->medicines->sum('paid_amount');

        // Calculate grand totals
        $totalAmount = $radiologyTotal + $labTotal + $medicineTotal;
        $totalPaid = $radiologyPaid + $labPaid + $medicinePaid;

        return view('op-registers.preview', compact(
            'opRegister',
            'totalAmount',
            'totalPaid',
            'radiologyTotal',
            'radiologyPaid',
            'labTotal',
            'labPaid',
            'medicineTotal',
            'medicinePaid'
        ));
    }

    public function printDetails(OpRegister $opRegister)
    {

        dd($opRegister);
        // Calculate totals
        $medicineTotal = $opRegister->medicines->sum(function ($medicine) {
            return $medicine->quantity * $medicine->price;
        });

        $radiologyTotal = $opRegister->radiologyTests->sum('price');
        $labTotal = $opRegister->labTests->sum('price');

        $totalAmount = $medicineTotal + $radiologyTotal + $labTotal;
        $totalPaid = $opRegister->medicines->sum('paid_amount') +
            $opRegister->radiologyTests->sum('paid_amount') +
            $opRegister->labTests->sum('paid_amount');

        return view('op-registers.print-details', compact(
            'opRegister',
            'medicineTotal',
            'radiologyTotal',
            'labTotal',
            'totalAmount',
            'totalPaid'
        ));
    }

    // Single OP register clinic print
    public function printOPReport(OpRegister $opRegister)
    {
        // Load the register with all necessary relationships
        $opRegister->load([
            'patient',
            'medicalOfficer',
            'labTests',
            'radiologyTests',
            'medicines'
        ]);

        // Wrap in collection for template compatibility
        $opRegisters = collect([$opRegister]);

        return view('op-registers.print-clinic', compact('opRegisters'));
    }
}
