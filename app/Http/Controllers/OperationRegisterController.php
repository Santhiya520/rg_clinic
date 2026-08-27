<?php
// app/Http/Controllers/Admin/OperationRegisterController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OperationRegister;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OperationRegister::with([
            'patient',
            'operatingSurgeon',
            'medicalOfficer'
        ])->latest();

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('patient_id', 'like', "%{$search}%");
                })
                    ->orWhere('hospital_ip_no', 'like', "%{$search}%")
                    ->orWhere('operation_performed', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('date_of_admission', $request->date);
        }

        $operationRegisters = $query->paginate(20);

        return view('operation-registers.index', compact('operationRegisters'));
    }

    public function generateHospitalIpNo()
    {
        $lastRecord = \App\Models\OperationRegister::orderBy('id', 'desc')->first();

        if ($lastRecord && preg_match('/OP(\d+)/', $lastRecord->hospital_ip_no, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }

        return 'OP' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $medicalOfficers = User::where('role', 'doctor')->orderBy('name')->get();
        $receptions = User::where('role', 'reception')->orderBy('name')->get();
        $staff = User::whereIn('role', ['doctor', 'reception'])->orderBy('name')->get();

        $hospitalIpNo = $this->generateHospitalIpNo();

        return view('operation-registers.create', compact(
            'patients',
            'medicalOfficers',
            'receptions',
            'staff',
            'hospitalIpNo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'operation_theatre_type' => 'nullable|string|max:100',
            'date_of_admission' => 'required|date',
            'hospital_ip_no' => 'required|string|max:50',
            'provisional_diagnosis' => 'nullable|string',
            'investigations' => 'nullable|string',
            'operation_performed' => 'required|string|max:255',
            'operating_surgeon_id' => 'required|exists:users,id',
            'assistant_surgeon_id' => 'nullable|exists:users,id',
            'anaesthetist_id' => 'nullable|exists:users,id',
            'staff_reception_id' => 'nullable|exists:users,id',
            'operation_start_time' => 'required|date_format:H:i',
            'operation_end_time' => 'required|date_format:H:i|after:operation_start_time',
            'operation_notes' => 'nullable|string',
            'transferred_to_ward' => 'required|string|max:100',
            'additional_information' => 'nullable|string',
            'medical_officer_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $validated['user_id'] = auth()->id();

            OperationRegister::create($validated);


            DB::commit();

            return redirect()->route('operation-registers.success')
                ->with('success', 'Operation register created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create operation register: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationRegister $operationRegister)
    {
        $operationRegister->load([
            'patient',
            'operatingSurgeon',
            'assistantSurgeon',
            'anaesthetist',
            'staffreception',
            'medicalOfficer'
        ]);

        return view('operation-registers.show', compact('operationRegister'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperationRegister $operationRegister)
    {
        $patients = Patient::orderBy('name')->get();
        $medicalOfficers = User::where('role', 'doctor')->orderBy('name')->get();
        $receptions = User::where('role', 'reception')->orderBy('name')->get();
        $staff = User::whereIn('role', ['doctor', 'reception'])->orderBy('name')->get();

        return view('operation-registers.edit', compact(
            'operationRegister',
            'patients',
            'medicalOfficers',
            'receptions',
            'staff'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperationRegister $operationRegister)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'operation_theatre_type' => 'nullable|string|max:100',
            'date_of_admission' => 'required|date',
            'hospital_ip_no' => 'required|string|max:50',
            'provisional_diagnosis' => 'nullable|string',
            'investigations' => 'nullable|string',
            'operation_performed' => 'required|string|max:255',
            'operating_surgeon_id' => 'required|exists:users,id',
            'assistant_surgeon_id' => 'nullable|exists:users,id',
            'anaesthetist_id' => 'nullable|exists:users,id',
            'staff_reception_id' => 'nullable|exists:users,id',
            'operation_start_time' => 'required|date_format:H:i',
            'operation_end_time' => 'required|date_format:H:i|after:operation_start_time',
            'operation_notes' => 'nullable|string',
            'transferred_to_ward' => 'required|string|max:100',
            'additional_information' => 'nullable|string',
            'medical_officer_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $operationRegister->update($validated);

            DB::commit();

            return redirect()->route('operation-registers.success')
                ->with('success', 'Operation register updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update operation register: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationRegister $operationRegister)
    {
        try {
            $operationRegister->delete();
            return redirect()->route('operation-registers.success')
                ->with('success', 'Operation register deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete operation register: ' . $e->getMessage());
        }
    }

    /**
     * Print operation register.
     */
    public function print(OperationRegister $operationRegister)
    {
        $operationRegister->load([
            'patient',
            'operatingSurgeon',
            'assistantSurgeon',
            'anaesthetist',
            'staffreception',
            'medicalOfficer'
        ]);

        return view('operation-registers.print', compact('operationRegister'));
    }

    /**
     * Success page.
     */
    public function success()
    {
        return view('operation-registers.success');
    }
}
