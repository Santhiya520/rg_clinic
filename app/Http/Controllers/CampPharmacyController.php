<?php

namespace App\Http\Controllers;

use App\Models\FreeCampPharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CampPharmacyController extends Controller
{
    public function index()
    {
        $campPharmacies = DB::select("
            SELECT * FROM camp_pharmacy
            WHERE deleted_at IS NULL
            ORDER BY token_number DESC
        ");

        return view('camp-pharmacy.index', compact('campPharmacies'));
    }

    public function create()
    {
        return view('camp-pharmacy.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token_number' => 'required|string|max:50',
            'patient_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'payment_type' => 'nullable|in:cash,card,upi,insurance',
            'payment_status' => 'nullable|in:pending,paid,partial',
            'total_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'medicines' => 'nullable',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if token already exists
        $existingToken = DB::selectOne("
            SELECT id FROM camp_pharmacy
            WHERE token_number = ? AND deleted_at IS NULL
        ", [$request->token_number]);

        if ($existingToken) {
            return redirect()->back()
                ->with('error', 'Token number already exists!')
                ->withInput();
        }

        // Prepare data
        $data = [
            'token_number' => $request->token_number,
            'patient_name' => $request->patient_name,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'payment_type' => $request->payment_type ?? 'cash',
            'payment_status' => $request->payment_status ?? 'pending',
            'total_amount' => $request->total_amount ?? 0,
            'paid_amount' => $request->paid_amount ?? 0,
            'remarks' => $request->remarks,
        ];

        // Calculate balance
        $data['balance_amount'] = ($request->total_amount ?? 0) - ($request->paid_amount ?? 0);

        // Generate bill number if total amount > 0
        if (($request->total_amount ?? 0) > 0) {
            $data['bill_date'] = date('Y-m-d');
            $data['bill_number'] = 'BIL-' . date('YmdHis') . rand(10, 99);
        }

        // Handle medicines (store as JSON)
        if ($request->has('medicines') && !empty($request->medicines)) {
            if (is_array($request->medicines)) {
                $data['medicines'] = json_encode($request->medicines);
            } else {
                $data['medicines'] = $request->medicines;
            }
        }

        // Insert record
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        DB::insert("
            INSERT INTO camp_pharmacy ($columns, created_at, updated_at)
            VALUES ($placeholders, NOW(), NOW())
        ", array_values($data));

        return redirect()->route('camp-pharmacy.index')
            ->with('success', 'Camp pharmacy record created successfully!');
    }

    public function show($id)
    {
        $record = DB::selectOne("
            SELECT * FROM camp_pharmacy
            WHERE id = ? AND deleted_at IS NULL
        ", [$id]);

        if (!$record) {
            return redirect()->route('camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        // Decode medicines JSON if exists
        if ($record->medicines) {
            $record->medicines = json_decode($record->medicines, true);
        }

        return view('camp-pharmacy.show', compact('record'));
    }

    public function edit($id)
    {
        $record = DB::selectOne("
            SELECT * FROM camp_pharmacy
            WHERE id = ? AND deleted_at IS NULL
        ", [$id]);

        if (!$record) {
            return redirect()->route('camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        // Decode medicines JSON if exists
        if ($record->medicines) {
            try {
                $record->medicines = json_decode($record->medicines, true);
            } catch (\Exception $e) {
                $record->medicines = $record->medicines;
            }
        }

        return view('camp-pharmacy.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'token_number' => 'required|string|max:50',
            'patient_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'payment_type' => 'nullable|in:cash,card,upi,insurance',
            'payment_status' => 'nullable|in:pending,paid,partial',
            'total_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'medicines' => 'nullable',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if token already exists for other records
        $existingToken = DB::selectOne("
            SELECT id FROM camp_pharmacy
            WHERE token_number = ? AND id != ? AND deleted_at IS NULL
        ", [$request->token_number, $id]);

        if ($existingToken) {
            return redirect()->back()
                ->with('error', 'Token number already exists!')
                ->withInput();
        }

        // Prepare data
        $data = [
            'token_number' => $request->token_number,
            'patient_name' => $request->patient_name,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'payment_type' => $request->payment_type ?? 'cash',
            'payment_status' => $request->payment_status ?? 'pending',
            'total_amount' => $request->total_amount ?? 0,
            'paid_amount' => $request->paid_amount ?? 0,
            'remarks' => $request->remarks,
        ];

        // Calculate balance
        $data['balance_amount'] = ($request->total_amount ?? 0) - ($request->paid_amount ?? 0);

        // Update bill info if not exists
        if (!$request->bill_number && ($request->total_amount ?? 0) > 0) {
            $existing = DB::selectOne("SELECT bill_number FROM camp_pharmacy WHERE id = ?", [$id]);
            if (!$existing->bill_number) {
                $data['bill_date'] = date('Y-m-d');
                $data['bill_number'] = 'BIL-' . date('YmdHis') . rand(10, 99);
            }
        }

        // Handle medicines
        if ($request->has('medicines') && !empty($request->medicines)) {
            if (is_array($request->medicines)) {
                $data['medicines'] = json_encode($request->medicines);
            } else {
                $data['medicines'] = $request->medicines;
            }
        } else {
            $data['medicines'] = null;
        }

        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';

        DB::update("
            UPDATE camp_pharmacy
            SET $setClause, updated_at = NOW()
            WHERE id = ? AND deleted_at IS NULL
        ", [...array_values($data), $id]);

        return redirect()->route('camp-pharmacy.index')
            ->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        DB::update("
            UPDATE camp_pharmacy
            SET deleted_at = NOW()
            WHERE id = ?
        ", [$id]);

        return redirect()->route('camp-pharmacy.index')
            ->with('success', 'Record deleted successfully!');
    }

    public function print($id)
    {
        $record = DB::selectOne("
            SELECT * FROM camp_pharmacy
            WHERE id = ? AND deleted_at IS NULL
        ", [$id]);

        if (!$record) {
            return redirect()->route('camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        // Decode medicines if needed
        if ($record->medicines) {
            try {
                $record->medicines = json_decode($record->medicines, true);
            } catch (\Exception $e) {
                $record->medicines = $record->medicines;
            }
        }

        return view('camp-pharmacy.print', compact('record'));
    }

    public function printThermal($id)
    {
        $record = DB::selectOne("
            SELECT * FROM camp_pharmacy
            WHERE id = ? AND deleted_at IS NULL
        ", [$id]);

        if (!$record) {
            return redirect()->route('camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        // Decode medicines if needed
        if ($record->medicines) {
            try {
                $record->medicines = json_decode($record->medicines, true);
            } catch (\Exception $e) {
                $record->medicines = $record->medicines;
            }
        }

        return view('camp-pharmacy.print-thermal', compact('record'));
    }

    public function freeIndex()
    {
        $freeCampPharmacies = FreeCampPharmacy::orderBy('token_number', 'desc')->get();

        return view('free-camp-pharmacy.index', compact('freeCampPharmacies'));
    }

    public function freeCreate()
    {
        return view('free-camp-pharmacy.create');
    }

    public function freeStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token_number' => 'required|string|max:50|unique:free_camp_pharmacy,token_number,NULL,id,deleted_at,NULL',
            'patient_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'medicines' => 'nullable',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'token_number' => $request->token_number,
            'patient_name' => $request->patient_name,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'remarks' => $request->remarks,
        ];

        // Handle medicines (store as JSON)
        if ($request->has('medicines') && !empty($request->medicines)) {
            $data['medicines'] = is_array($request->medicines)
                ? $request->medicines
                : json_decode($request->medicines, true);
        }

        FreeCampPharmacy::create($data);

        return redirect()->route('free-camp-pharmacy.index')
            ->with('success', 'Free camp pharmacy record created successfully!');
    }

    public function freeShow($id)
    {
        $record = FreeCampPharmacy::find($id);

        if (!$record) {
            return redirect()->route('free-camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        return view('free-camp-pharmacy.show', compact('record'));
    }

    public function freeEdit($id)
    {
        $record = FreeCampPharmacy::find($id);

        if (!$record) {
            return redirect()->route('free-camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        return view('free-camp-pharmacy.edit', compact('record'));
    }

    public function freeUpdate(Request $request, $id)
    {
        $record = FreeCampPharmacy::find($id);

        if (!$record) {
            return redirect()->route('free-camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        $validator = Validator::make($request->all(), [
            'token_number' => 'required|string|max:50|unique:free_camp_pharmacy,token_number,' . $id . ',id,deleted_at,NULL',
            'patient_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'medicines' => 'nullable',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'token_number' => $request->token_number,
            'patient_name' => $request->patient_name,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'remarks' => $request->remarks,
        ];

        // Handle medicines (store as JSON)
        if ($request->has('medicines')) {
            if (empty($request->medicines)) {
                $data['medicines'] = null;
            } else {
                $data['medicines'] = is_array($request->medicines)
                    ? $request->medicines
                    : json_decode($request->medicines, true);
            }
        }

        $record->update($data);

        return redirect()->route('free-camp-pharmacy.index')
            ->with('success', 'Free camp pharmacy record updated successfully!');
    }

    public function freeDestroy($id)
    {
        $record = FreeCampPharmacy::find($id);

        if (!$record) {
            return redirect()->route('free-camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        $record->delete();

        return redirect()->route('free-camp-pharmacy.index')
            ->with('success', 'Free camp pharmacy record deleted successfully!');
    }

    public function freePrint($id)
    {
        $record = FreeCampPharmacy::find($id);

        if (!$record) {
            return redirect()->route('free-camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        return view('free-camp-pharmacy.print', compact('record'));
    }

    public function freePrintThermal($id)
    {
        $record = FreeCampPharmacy::find($id);

        if (!$record) {
            return redirect()->route('free-camp-pharmacy.index')
                ->with('error', 'Record not found!');
        }

        return view('free-camp-pharmacy.print-thermal', compact('record'));
    }
}
