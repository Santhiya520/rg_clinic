@extends('layouts.app')

@section('title', 'Radiology Dashboard')
@section('page-title', 'Radiology Dashboard')

@section('content')
<div class="nk-block">
    <div class="row g-gs">

        <!-- OP -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-primary h-100">
                <div class="card-inner">
                    <span class="fs-2 text-white">{{ $opRadiologyCount }}</span>
                    <h6 class="text-white mt-1">OP Cases Today</h6>
                </div>
            </div>
        </div>

        <!-- IP -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-success h-100">
                <div class="card-inner">
                    <span class="fs-2 text-white">{{ $ipRadiologyCount }}</span>
                    <h6 class="text-white mt-1">IP Cases Today</h6>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-warning h-100">
                <div class="card-inner">
                    <span class="fs-2 text-white">{{ $pendingRadiology }}</span>
                    <h6 class="text-white mt-1">Pending Tests</h6>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-info h-100">
                <div class="card-inner">
                    <span class="fs-2 text-white">{{ $completedRadiology }}</span>
                    <h6 class="text-white mt-1">Completed Tests</h6>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-lg-3 col-sm-6 mt-3">
            <div class="card bg-danger h-100">
                <div class="card-inner">
                    <span class="fs-5 text-white">₹{{ number_format($todayRevenue, 2) }}</span>
                    <h6 class="text-white mt-1">Today's Revenue</h6>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
