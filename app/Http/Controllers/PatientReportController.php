<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\OpRegister;
use App\Models\InpatientRegister;
use App\Models\OperationRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PatientReportController extends Controller
{
    /**
     * Show patient dashboard
     */
    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }

        // Load recent activities
        $patient->load([
            'opRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            },
            'inpatientRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            },
            'operationRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            }
        ]);

        // Prepare recent activities
        $recentActivities = [];

        // OP Visits
        foreach ($patient->opRegisters as $op) {
            $recentActivities[] = [
                'date' => $op->created_at,
                'type' => 'OP Visit',
                'type_color' => 'primary',
                'title' => 'Token: ' . $op->token_number,
                'description' => $op->provisional_diagnosis,
                'doctor' => $op->medicalOfficer->name ?? 'N/A',
                'status' => 'Completed',
                'status_color' => 'success'
            ];
        }

        // IP Admissions
        foreach ($patient->inpatientRegisters as $ip) {
            $recentActivities[] = [
                'date' => $ip->created_at,
                'type' => 'IP Admission',
                'type_color' => 'info',
                'title' => 'IP No: ' . $ip->ip_no,
                'description' => $ip->diagnosis,
                'doctor' => $ip->medicalOfficer->name ?? 'N/A',
                'status' => $ip->discharge_date ? 'Discharged' : 'Admitted',
                'status_color' => $ip->discharge_date ? 'success' : 'warning'
            ];
        }

        // Operations
        foreach ($patient->operationRegisters as $operation) {
            $recentActivities[] = [
                'date' => $operation->created_at,
                'type' => 'Operation',
                'type_color' => 'warning',
                'title' => $operation->operation ?? 'Operation',
                'description' => $operation->remarks,
                'doctor' => $operation->operatingSurgeon->name ?? 'N/A',
                'status' => 'Completed',
                'status_color' => 'success'
            ];
        }

        // Sort by date
        usort($recentActivities, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        // Take only 5 recent activities
        $recentActivities = array_slice($recentActivities, 0, 5);

        // Get counts for dashboard
        $opVisitsCount = $patient->opRegisters->count();
        $ipAdmissionsCount = $patient->inpatientRegisters->count();
        $operationsCount = $patient->operationRegisters->count();

        // Calculate total amount
        $totalAmount = 0;
        foreach ($patient->opRegisters as $op) {
            $totalAmount += $op->radiologyTests->sum('price') +
                $op->labTests->sum('price') +
                $op->medicines->sum('price');
        }
        foreach ($patient->inpatientRegisters as $ip) {
            $totalAmount += $ip->radiologyTests->sum('price') +
                $ip->labTests->sum('price') +
                $ip->medicines->sum('price');
        }
        $totalAmount += $patient->operationRegisters->sum('total_amount') ?? 0;

        return view('online.dashboard', compact(
            'patient',
            'opVisitsCount',
            'ipAdmissionsCount',
            'operationsCount',
            'totalAmount',
            'recentActivities'
        ));
    }

    /**
     * Show patient's detailed report (with tabs)
     */
    public function myReport(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }

        // Get date filters from request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Initialize variables as empty
        $totalOpVisits = 0;
        $totalIpAdmissions = 0;
        $totalOperations = 0;
        $totalOpAmount = 0;
        $totalIpAmount = 0;
        $totalOperationAmount = 0;
        $totalAmount = 0;
        $totalRadiologyTests = 0;
        $totalLabTests = 0;
        $totalMedicines = 0;

        // Create empty collections to avoid errors in view
        $patient->setRelation('opRegisters', collect());
        $patient->setRelation('inpatientRegisters', collect());
        $patient->setRelation('operationRegisters', collect());

        // Only load and calculate data if BOTH dates are provided
        if ($fromDate && $toDate) {
            // Validate date range (to_date should be after from_date)
            if (strtotime($fromDate) > strtotime($toDate)) {
                return redirect()->back()->with('error', 'From date cannot be greater than To date.');
            }

            // Base query for patient data
            $query = [
                'opRegisters' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    $query->whereDate('created_at', '>=', $fromDate);
                    $query->whereDate('created_at', '<=', $toDate);
                },
                'inpatientRegisters' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    $query->whereDate('created_at', '>=', $fromDate);
                    $query->whereDate('created_at', '<=', $toDate);
                },
                'operationRegisters' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    $query->whereDate('created_at', '>=', $fromDate);
                    $query->whereDate('created_at', '<=', $toDate);
                }
            ];

            $patient->load($query);

            // Calculate totals with date filter
            $totalOpVisits = $patient->opRegisters->count();
            $totalIpAdmissions = $patient->inpatientRegisters->count();
            $totalOperations = $patient->operationRegisters->count();

            // OP Amounts
            $totalOpAmount = $patient->opRegisters->sum(function ($register) {
                return $register->radiologyTests->sum('price') +
                    $register->labTests->sum('price') +
                    $register->medicines->sum('price');
            });

            // IP Amounts
            $totalIpAmount = $patient->inpatientRegisters->sum(function ($register) {
                return $register->radiologyTests->sum('price') +
                    $register->labTests->sum('price') +
                    $register->medicines->sum('price');
            });

            // Operation amounts
            $totalOperationAmount = $patient->operationRegisters->sum('total_amount') ?? 0;

            $totalAmount = $totalOpAmount + $totalIpAmount + $totalOperationAmount;

            // Counts for display
            $totalRadiologyTests = $patient->opRegisters->sum(function ($r) {
                return $r->radiologyTests->count();
            }) + $patient->inpatientRegisters->sum(function ($r) {
                return $r->radiologyTests->count();
            });

            $totalLabTests = $patient->opRegisters->sum(function ($r) {
                return $r->labTests->count();
            }) + $patient->inpatientRegisters->sum(function ($r) {
                return $r->labTests->count();
            });

            $totalMedicines = $patient->opRegisters->sum(function ($r) {
                return $r->medicines->count();
            }) + $patient->inpatientRegisters->sum(function ($r) {
                return $r->medicines->count();
            });
        }

        // Get date range for display
        $dateRange = '';
        $hasFilter = false;

        if ($fromDate && $toDate) {
            $dateRange = date('d M Y', strtotime($fromDate)) . ' to ' . date('d M Y', strtotime($toDate));
            $hasFilter = true;
        }

        return view('online.patient.report-details', compact(
            'patient',
            'totalOpVisits',
            'totalIpAdmissions',
            'totalOperations',
            'totalAmount',
            'totalOpAmount',
            'totalIpAmount',
            'totalOperationAmount',
            'totalRadiologyTests',
            'totalLabTests',
            'totalMedicines',
            'fromDate',
            'toDate',
            'dateRange',
            'hasFilter'
        ));
    }

    /**
     * Show patient's reports list
     */
    public function myReports()
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }

        // Load patient's data
        $patient->load([
            'opRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'inpatientRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'operationRegisters' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return view('online.patient.reports', compact('patient'));
    }

    /**
     * View OP details
     */
    public function viewOpDetails(OpRegister $opRegister)
    {
        $patient = Auth::guard('patient')->user();

        // Verify patient owns this record
        if ($opRegister->patient_id != $patient->id) {
            abort(403, 'Unauthorized access.');
        }

        $opRegister->load([
            'medicalOfficer',
            'radiologyTests.radiologyTest',
            'labTests.labTest',
            'medicines.medicine'
        ]);

        return view('online.patient.op-view', compact('opRegister', 'patient'));
    }

    /**
     * View IP details
     */
    public function viewIpDetails(InpatientRegister $inpatientRegister)
    {
        $patient = Auth::guard('patient')->user();

        // Verify patient owns this record
        if ($inpatientRegister->patient_id != $patient->id) {
            abort(403, 'Unauthorized access.');
        }

        $inpatientRegister->load([
            'medicalOfficer',
            'radiologyTests.radiologyTest',
            'labTests.labTest',
            'medicines.medicine'
        ]);

        return view('online.patient.ip-view', compact('inpatientRegister', 'patient'));
    }

    /**
     * View Operation details
     */
    public function viewOperationDetails(OperationRegister $operationRegister)
    {
        $patient = Auth::guard('patient')->user();

        // Verify patient owns this record
        if ($operationRegister->patient_id != $patient->id) {
            abort(403, 'Unauthorized access.');
        }

        $operationRegister->load([
            'operatingSurgeon',
            'assistantSurgeon',
            'anaesthetist',
            'medicalOfficer'
        ]);

        return view('online.patient.operation-view', compact('operationRegister', 'patient'));
    }

    public function patientRadiologyReports(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }

        // Get date filters from request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Initialize variables as empty
        $totalTests = 0;
        $completedTests = 0;
        $pendingTests = 0;
        $allRadiologyTests = collect();

        // Only load data if BOTH dates are provided
        if ($fromDate && $toDate) {
            // Validate date range (to_date should be after from_date)
            if (strtotime($fromDate) > strtotime($toDate)) {
                return redirect()->back()->with('error', 'From date cannot be greater than To date.');
            }

            // Load patient's radiology tests from both OP and IP registers
            $patient->load([
                'opRegisters.radiologyTests.radiologyTest' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    if ($fromDate) {
                        $query->whereDate('created_at', '>=', $fromDate);
                    }
                    if ($toDate) {
                        $query->whereDate('created_at', '<=', $toDate);
                    }
                },
                'inpatientRegisters.radiologyTests.radiologyTest' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    if ($fromDate) {
                        $query->whereDate('created_at', '>=', $fromDate);
                    }
                    if ($toDate) {
                        $query->whereDate('created_at', '<=', $toDate);
                    }
                }
            ]);

            // Collect all radiology tests
            $allRadiologyTests = collect();

            // Add OP radiology tests
            foreach ($patient->opRegisters as $opRegister) {
                foreach ($opRegister->radiologyTests as $test) {
                    $test->type = 'OP';
                    $test->visit_date = $opRegister->created_at;
                    $test->token_or_ip = 'Token: ' . $opRegister->token_number;
                    $test->doctor = $opRegister->medicalOfficer->name ?? 'N/A';
                    $allRadiologyTests->push($test);
                }
            }

            // Add IP radiology tests
            foreach ($patient->inpatientRegisters as $ipRegister) {
                foreach ($ipRegister->radiologyTests as $test) {
                    $test->type = 'IP';
                    $test->visit_date = $ipRegister->created_at;
                    $test->token_or_ip = 'IP No: ' . $ipRegister->ip_no;
                    $test->doctor = $ipRegister->medicalOfficer->name ?? 'N/A';
                    $allRadiologyTests->push($test);
                }
            }

            // Sort by date (newest first)
            $allRadiologyTests = $allRadiologyTests->sortByDesc('visit_date');

            // Get counts
            $totalTests = $allRadiologyTests->count();
            $completedTests = $allRadiologyTests->where('status', 'completed')->count();
            $pendingTests = $allRadiologyTests->where('status', 'pending')->count();
        }

        // Get date range for display
        $dateRange = '';
        $hasFilter = false;

        if ($fromDate && $toDate) {
            $dateRange = date('d M Y', strtotime($fromDate)) . ' to ' . date('d M Y', strtotime($toDate));
            $hasFilter = true;
        }

        return view('online.patient.radiology-reports', compact(
            'patient',
            'allRadiologyTests',
            'totalTests',
            'completedTests',
            'pendingTests',
            'fromDate',
            'toDate',
            'dateRange',
            'hasFilter'
        ));
    }
    public function patientLabReports(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }

        // Get date filters from request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Initialize variables as empty
        $totalTests = 0;
        $completedTests = 0;
        $pendingTests = 0;
        $allLabTests = collect();

        // Only load data if BOTH dates are provided
        if ($fromDate && $toDate) {
            // Validate date range (to_date should be after from_date)
            if (strtotime($fromDate) > strtotime($toDate)) {
                return redirect()->back()->with('error', 'From date cannot be greater than To date.');
            }

            // Load patient's lab tests from both OP and IP registers
            $patient->load([
                'opRegisters.labTests.labTest' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    if ($fromDate) {
                        $query->whereDate('created_at', '>=', $fromDate);
                    }
                    if ($toDate) {
                        $query->whereDate('created_at', '<=', $toDate);
                    }
                },
                'inpatientRegisters.labTests.labTest' => function ($query) use ($fromDate, $toDate) {
                    $query->orderBy('created_at', 'desc');
                    if ($fromDate) {
                        $query->whereDate('created_at', '>=', $fromDate);
                    }
                    if ($toDate) {
                        $query->whereDate('created_at', '<=', $toDate);
                    }
                }
            ]);

            // Collect all lab tests
            $allLabTests = collect();

            // Add OP lab tests
            foreach ($patient->opRegisters as $opRegister) {
                foreach ($opRegister->labTests as $test) {
                    $test->type = 'OP';
                    $test->visit_date = $opRegister->created_at;
                    $test->token_or_ip = 'Token: ' . $opRegister->token_number;
                    $test->doctor = $opRegister->medicalOfficer->name ?? 'N/A';
                    $test->load('labTest');
                    $allLabTests->push($test);
                }
            }

            // Add IP lab tests
            foreach ($patient->inpatientRegisters as $ipRegister) {
                foreach ($ipRegister->labTests as $test) {
                    $test->type = 'IP';
                    $test->visit_date = $ipRegister->created_at;
                    $test->token_or_ip = 'IP No: ' . $ipRegister->ip_no;
                    $test->doctor = $ipRegister->medicalOfficer->name ?? 'N/A';
                    $test->load('labTest');
                    $allLabTests->push($test);
                }
            }

            // Sort by date (newest first)
            $allLabTests = $allLabTests->sortByDesc('visit_date');

            // Get counts
            $totalTests = $allLabTests->count();
            $completedTests = $allLabTests->where('status', 'completed')->count();
            $pendingTests = $allLabTests->where('status', 'pending')->count();
        }

        // Get date range for display
        $dateRange = '';
        $hasFilter = false;

        if ($fromDate && $toDate) {
            $dateRange = date('d M Y', strtotime($fromDate)) . ' to ' . date('d M Y', strtotime($toDate));
            $hasFilter = true;
        }

        return view('online.patient.lab-reports', compact(
            'patient',
            'allLabTests',
            'totalTests',
            'completedTests',
            'pendingTests',
            'fromDate',
            'toDate',
            'dateRange',
            'hasFilter'
        ));
    }
    public function viewLabTestResult($testId)
{
    $patient = Auth::guard('patient')->user();

    if (!$patient) {
        abort(403, 'Unauthorized access.');
    }

    // Find the test - need to search through both OP and IP registers
    $foundTest = null;
    $source = null;
    $register = null;

    // Search in OP registers
    foreach ($patient->opRegisters as $opRegister) {
        $test = $opRegister->labTests->where('id', $testId)->first();
        if ($test) {
            $foundTest = $test;
            $source = 'op';
            $register = $opRegister;
            break;
        }
    }

    // If not found in OP, search in IP registers
    if (!$foundTest) {
        foreach ($patient->inpatientRegisters as $ipRegister) {
            $test = $ipRegister->labTests->where('id', $testId)->first();
            if ($test) {
                $foundTest = $test;
                $source = 'ip';
                $register = $ipRegister;
                break;
            }
        }
    }

    if (!$foundTest) {
        abort(404, 'Lab test not found or you do not have permission to view it.');
    }

    // Check if test is completed
    if ($foundTest->status != 'completed') {
        abort(403, 'Lab test results are not available yet.');
    }

    // Load relationships
    $foundTest->load(['labTest', 'subTests']);

    if ($source == 'op') {
        $register->load('patient', 'medicalOfficer');
    } else {
        $register->load('patient', 'medicalOfficer');
    }

    return view('online.patient.lab-test-view', compact(
        'foundTest',
        'register',
        'source',
        'patient'
    ));
}
public function printLabTestResult($testId)
{
    $patient = Auth::guard('patient')->user();

    if (!$patient) {
        abort(403, 'Unauthorized access.');
    }

    // Find the test - need to search through both OP and IP registers
    $foundTest = null;
    $source = null;
    $register = null;

    // Search in OP registers
    foreach ($patient->opRegisters as $opRegister) {
        $test = $opRegister->labTests->where('id', $testId)->first();
        if ($test) {
            $foundTest = $test;
            $source = 'op';
            $register = $opRegister;
            break;
        }
    }

    // If not found in OP, search in IP registers
    if (!$foundTest) {
        foreach ($patient->inpatientRegisters as $ipRegister) {
            $test = $ipRegister->labTests->where('id', $testId)->first();
            if ($test) {
                $foundTest = $test;
                $source = 'ip';
                $register = $ipRegister;
                break;
            }
        }
    }

    if (!$foundTest) {
        abort(404, 'Lab test not found or you do not have permission to view it.');
    }

    // Check if test is completed
    if ($foundTest->status != 'completed') {
        abort(403, 'Lab test results are not available yet.');
    }

    // Load relationships
    $foundTest->load(['labTest', 'subTests']);

    if ($source == 'op') {
        $register->load('patient', 'medicalOfficer');
    } else {
        $register->load('patient', 'medicalOfficer');
    }

    return view('online.patient.print-lab-report', compact(
        'foundTest',
        'register',
        'source',
        'patient'
    ));
}
}
