<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\OpRegister;

class PatientAppointmentController extends Controller
{
    /**
     * Display list of online appointments
     */
    public function index()
    {
        $patient = Auth::guard('patient')->user();

        $appointments = DB::table('op_registers')
            ->where('patient_id', $patient->id)
            ->where('is_online_appointment', 1)
            ->select('op_registers.*', 'users.name as doctor_name')
            ->leftJoin('users', 'op_registers.medical_officer_id', '=', 'users.id')
            ->orderBy('op_registers.date', 'desc')
            ->paginate(10);

        return view('online.appointments.index', compact('patient', 'appointments'));
    }

    /**
     * Show form to create new appointment
     */
    public function create()
    {
        $patient = Auth::guard('patient')->user();

        // Get active doctors (users with role 'doctor')
        $doctors = DB::table('users')
            ->where('role', 'doctor')
            ->orWhere('role', 'medical_officer')
            ->orderBy('name')
            ->get();

        // Get next available dates (next 30 days)
        $minDate = Carbon::today()->format('Y-m-d');
        $maxDate = Carbon::tomorrow()->addDays(30)->format('Y-m-d');

        return view('online.appointments.create', compact('patient', 'doctors', 'minDate', 'maxDate'));
    }

    /**
     * Store new appointment
     */
    public function store(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'medical_officer_id' => 'required|exists:users,id',
        ]);

        try {
            // Check if patient already has an appointment on this date
            // Use the exact ENUM value 'registered' for checking

            // Generate OP number


            // Get next available token number for the doctor on this date
            $doctorAppointments = DB::table('op_registers')
                ->where('medical_officer_id', $request->medical_officer_id)
                ->where('date', $request->date)
                ->whereIn('status', ['registered', 'in_progress'])
                ->count();



            // Use the exact ENUM value 'registered' for new appointments
            $status = 'registered';

            // Create appointment with minimal required fields
            $appointmentId = DB::table('op_registers')->insertGetId([
                'op_no' => $request->op_no,
                'patient_id' => $patient->id,
                'token_number' => $request->token_number,
                'date' => $request->date,
                'medical_officer_id' => $request->medical_officer_id,
                'status' => $status, // 'registered' - exact ENUM value
                'is_online_appointment' => 1,

                // Required fields with defaults
                'weight' => null,
                'height' => null,
                'pluse' => null,
                'spo2' => null,
                'bp' => null,
                'temparature' => null,
                'provisional_diagnosis' => null,
                'investigations' => null,
                'final_diagnosis' => null,
                'treatment' => null,
                'result' => null,
                'overall_discount_percentage' => 0,
                'overall_discount_amount' => 0,
                'paid_amount' => 0,
                'payment_type' => null,
                'payment_reference' => null,
                'paid_at' => null,
                'additional_information' => 'Booked online by patient',
                'doctor_fees' => 0,
                'pharmacy_amount' => 0,
                'lab_total_amount' => 0,
                'radiology_total_amount' => 0,
                'total' => 0,
                'paid_status' => 'pending',
                'pharmacy_issued_at' => null,
                'user_id' => null,
                'total_discount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log successful booking
            \Log::info('Online appointment booked successfully', [
                'patient_id' => $patient->id,
                'appointment_id' => $appointmentId,
                'token_number' => $request->token_number,
                'date' => $request->date,
                'doctor_id' => $request->medical_officer_id,
                'status' => $status
            ]);

            return redirect()->route('patient.appointments')
                ->with('success', 'Appointment booked successfully! Token Number: ' . $request->token_number);
        } catch (\Exception $e) {
            dd($e);
            \Log::error('Failed to book online appointment', [
                'error' => $e->getMessage(),
                'patient_id' => $patient->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to book appointment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show appointment details
     */
    public function show($id)
    {
        $patient = Auth::guard('patient')->user();

        $appointment = DB::table('op_registers')
            ->where('op_registers.id', $id) // Specify op_registers.id
            ->where('patient_id', $patient->id)
            ->where('is_online_appointment', 1)
            ->select('op_registers.*', 'users.name as doctor_name')
            ->leftJoin('users', 'op_registers.medical_officer_id', '=', 'users.id')
            ->first();

        if (!$appointment) {
            abort(404);
        }

        return view('online.appointments.show', compact('patient', 'appointment'));
    }

    /**
     * Show form to edit appointment
     */
    public function edit($id)
    {
        $patient = Auth::guard('patient')->user();

        $appointment = DB::table('op_registers')
            ->where('id', $id)
            ->where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->where('is_online_appointment', 1)
            ->first();

        if (!$appointment) {
            return redirect()->route('patient.appointments')
                ->with('error', 'Appointment cannot be edited.');
        }

        // Get active doctors
        $doctors = DB::table('users')
            ->where('role', 'doctor')
            ->orWhere('role', 'medical_officer')
            ->orderBy('name')
            ->get();

        $minDate = Carbon::today()->format('Y-m-d');
        $maxDate = Carbon::tomorrow()->addDays(30)->format('Y-m-d');

        return view('online.appointments.edit', compact('patient', 'appointment', 'doctors', 'minDate', 'maxDate'));
    }

    /**
     * Update appointment
     */
    public function update(Request $request, $id)
    {
        $patient = Auth::guard('patient')->user();

        $appointment = DB::table('op_registers')
            ->where('id', $id)
            ->where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->first();

        if (!$appointment) {
            return redirect()->route('patient.appointments')
                ->with('error', 'Appointment cannot be updated.');
        }

        $request->validate([
            'date' => 'required|date|after:today',
            'medical_officer_id' => 'required|exists:users,id',
        ]);

        // Check if new date already has an appointment (excluding current)
        $existingAppointment = DB::table('op_registers')
            ->where('patient_id', $patient->id)
            ->where('date', $request->date)
            ->where('id', '!=', $id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'You already have an appointment on this date.')->withInput();
        }

        // Update appointment
        DB::table('op_registers')
            ->where('id', $id)
            ->update([
                'date' => $request->date,
                'medical_officer_id' => $request->medical_officer_id,
                'updated_at' => now(),
            ]);

        return redirect()->route('patient.appointments')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Cancel appointment
     */
    public function destroy($id)
    {

        // Delete the record
        DB::table('op_registers')
            ->where('id', $id)
            ->delete();

        return redirect()->route('patient.appointments')
            ->with('success', 'Appointment deleted successfully!');
    }
}
