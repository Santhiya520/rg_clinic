@extends('layouts.app')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Doctor Dashboard')

@section('content')
<div class="nk-block">
    <div class="row g-gs">

        <!-- OP -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-primary">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-2 text-white">{{ $todayOpCount }}</span>
                            <h6 class="text-white mt-1">Today's OP</h6>
                        </div>
                        <em class="icon ni ni-user-fill text-white" style="font-size:2rem"></em>
                    </div>
                </div>
            </div>
        </div>

        <!-- IP -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-success">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-2 text-white">{{ $todayIpCount }}</span>
                            <h6 class="text-white mt-1">Today's IP</h6>
                        </div>
                        <em class="icon ni ni-hospital-fill text-white" style="font-size:2rem"></em>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-warning">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-2 text-white">{{ $todayOperationCount }}</span>
                            <h6 class="text-white mt-1">Today's Operations</h6>
                        </div>
                        <em class="icon ni ni-activity-fill text-white" style="font-size:2rem"></em>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="col-lg-3 col-sm-6">
            <div class="card h-100 bg-danger">
                <div class="card-inner">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-2 text-white">{{ $totalCases }}</span>
                            <h6 class="text-white mt-1">Total Today</h6>
                        </div>
                        <em class="icon ni ni-summary-fill text-white" style="font-size:2rem"></em>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
