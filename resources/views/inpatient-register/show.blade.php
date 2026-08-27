@extends('layouts.app')

@section('title', 'Inpatient Record Details')
@section('page-title', 'Inpatient Record Details')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">Inpatient Record - {{ $inpatientRegister->hospital_ip_no }}</h5>
                            <p class="text-soft">Created: {{ $inpatientRegister->created_at->format('d/m/Y h:i A') }} |
                                Updated: {{ $inpatientRegister->updated_at->format('d/m/Y h:i A') }}</p>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="btn-group">
                                <a href="{{ route('inpatient-register.edit', $inpatientRegister) }}"
                                    class="btn btn-primary">
                                    <em class="icon ni ni-edit"></em> &nbsp; Edit Record
                                </a>
                                <a href="{{ route('inpatient-register.index') }}" class="btn btn-secondary">
                                    <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Information -->
                <div class="nk-block-head mt-4">
                    <div class="nk-block-head-content">
                        <h6 class="title">Patient Information</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-details">
                            <tr>
                                <th width="40%">Patient Name</th>
                                <td>{{ $inpatientRegister->patient->name }}</td>
                            </tr>
                            <tr>
                                <th>Patient ID</th>
                                <td>
                                    <span class="badge bg-outline-primary"
                                        style="padding:2px 8px">{{ $inpatientRegister->patient->patient_id }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $inpatientRegister->patient->address }}</td>
                            </tr>
                            <tr>
                                <th>Mobile No.</th>
                                <td>
                                    <a href="tel:{{ $inpatientRegister->patient->mobile }}"
                                        style="font-size: 14px; color:#000">
                                        <em class="icon ni ni-call"></em> {{ $inpatientRegister->patient->mobile }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-details">
                            <tr>
                                <th width="40%">Age</th>
                                <td>{{ $inpatientRegister->patient->age }} years</td>
                            </tr>
                            <tr>
                                <th>Sex</th>
                                <td>
                                    <span style="padding:2px 8px"
                                        class="badge bg-{{ $inpatientRegister->patient->sex == 'Male' ? 'primary' : 'pink' }}">
                                        {{ $inpatientRegister->patient->sex }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Hospital IP No.</th>
                                <td>
                                    <strong class="text-primary">{{ $inpatientRegister->hospital_ip_no }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>Medical Officer</th>
                                <td>
                                    @if ($inpatientRegister->doctor)
                                        <span style="padding:2px 8px" class="badge bg-outline-secondary">
                                            Dr. {{ $inpatientRegister->doctor->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Admission & Discharge Details -->
                <div class="nk-block-head mt-4">
                    <div class="nk-block-head-content">
                        <h6 class="title ">Admission & Discharge Details</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-details">
                            <tr>
                                <th width="40%">Date of Admission</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <em class="icon ni ni-calendar text-primary mr-2" style="font-size: 18px"></em>
                                        &nbsp;
                                        {{ $inpatientRegister->date_of_admission->format('d/m/Y') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Duration of Stay</th>
                                <td>
                                    @php
                                        if ($inpatientRegister->date_of_discharge) {
                                            $stayDuration = $inpatientRegister->date_of_admission->diffInDays(
                                                $inpatientRegister->date_of_discharge,
                                            );
                                            echo '<span class="badge bg-info" style="padding:2px 8px">' .
                                                $stayDuration .
                                                ' days</span>';
                                        } else {
                                            echo '<span class="badge bg-warning" style="padding:2px 8px">Still admitted</span>';
                                        }
                                    @endphp
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-details">
                            <tr>
                                <th width="40%">Date of Discharge</th>
                                <td>
                                    @if ($inpatientRegister->date_of_discharge)
                                        <div class="d-flex align-items-center">
                                            <em class="icon ni ni-calendar text-primary mr-2" style="font-size: 18px"></em>
                                            &nbsp;
                                            {{ $inpatientRegister->date_of_discharge->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <span class="badge bg-warning" style="padding:2px 8px">Not discharged yet</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Result</th>
                                <td>
                                    @php
                                        $resultClass = [
                                            'Cured' => 'success',
                                            'Same condition' => 'warning',
                                            'Referred' => 'info',
                                            'Expired' => 'danger',
                                        ][$inpatientRegister->result];
                                    @endphp
                                    <span style="padding:2px 8px" class="badge bg-{{ $resultClass }}">
                                        {{ $inpatientRegister->result }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="nk-block-head mt-4">
                    <div class="nk-block-head-content">
                        <h6 class="title">Medical Information</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-details">
                            <tr>
                                <th width="20%">Provisional Diagnosis</th>
                                <td>
                                    <div class="medical-content">
                                        {{ $inpatientRegister->provisional_diagnosis }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Investigations</th>
                                <td>
                                    <div class="medical-content">
                                        @if ($inpatientRegister->investigations)
                                            {{ $inpatientRegister->investigations }}
                                        @else
                                            <span class="text-muted">No investigations recorded</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Final Diagnosis</th>
                                <td>
                                    <div class="medical-content">
                                        {{ $inpatientRegister->final_diagnosis }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Treatment</th>
                                <td>
                                    <div class="medical-content">
                                        {{ $inpatientRegister->treatment }}
                                    </div>
                                </td>
                            </tr>
                            @if ($inpatientRegister->additional_info)
                                <tr>
                                    <th>Additional Information</th>
                                    <td>
                                        <div class="medical-content">
                                            {{ $inpatientRegister->additional_info }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <div class="btn-group">
                        <a href="{{ route('inpatient-register.edit', $inpatientRegister) }}" class="btn btn-primary">
                            <em class="icon ni ni-edit"></em> &nbsp; Edit Record
                        </a>
                        <form action="{{ route('inpatient-register.destroy', $inpatientRegister) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this inpatient record? This action cannot be undone.')"
                                style="border-radius: 0px">
                                <em class="icon ni ni-trash"></em>&nbsp; Delete Record
                            </button>
                        </form>
                        <a href="{{ route('inpatient-register.index') }}" class="btn btn-secondary"
                            style="border-radius: 0px">
                            <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                        </a>
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <em class="icon ni ni-printer"></em>&nbsp; Print Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style media="print">
        /* Page format */

        .btn,
        .btn-group,
        .nk-block-between,
        .nk-header,
        .nk-sidebar,
        .nk-footer,
        .text-soft,
        .icon,
        nav,
        header,
        footer {
            display: none !important;
        }

        @page {
            size: A4;
            margin: 5mm;
        }

        .table-details {
            background: #fff;
        }

        .table-details th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .medical-content {
            white-space: pre-line;
            line-height: 1.6;
        }

        .nk-block-head .title {
            border-left: 4px solid #6576ff;
            padding-left: 12px;
        }
    </style>

    <!-- Print Styles -->
    <style media="print">
        .btn-group,
        .nk-block-head .text-soft {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table-details th {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact;
        }
    </style>
@endsection
