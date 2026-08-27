@extends('layouts.app')

@section('title', 'Pharmacy Dashboard')
@section('page-title', 'Pharmacy Dashboard')

@section('content')
<div class="nk-block">
    <div class="row g-gs">

        <!-- OP Prescriptions -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-primary h-100">
                <div class="card-inner">
                    <span class="fs-2 text-white">{{ $opPrescriptionCount }}</span>
                    <h6 class="text-white mt-1">OP Prescriptions</h6>
                </div>
            </div>
        </div>

        <!-- IP Prescriptions -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-success h-100">
                <div class="card-inner">
                    <span class="fs-2 text-white">{{ $ipPrescriptionCount }}</span>
                    <h6 class="text-white mt-1">IP Prescriptions</h6>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-warning h-100">
                <div class="card-inner">
                    <span class="fs-5 text-white">₹{{ number_format($todaySalesAmount, 2) }}</span>
                    <h6 class="text-white mt-1">Today Sales</h6>
                </div>
            </div>
        </div>

        <!-- Purchases -->
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-danger h-100">
                <div class="card-inner">
                    <span class="fs-5 text-white">₹{{ number_format($todayPurchaseAmount, 2) }}</span>
                    <h6 class="text-white mt-1">Today Purchases</h6>
                </div>
            </div>
        </div>

    </div>

    <!-- Low Stock -->
    <div class="nk-block mt-4">
        <h5 class="mb-3">Low Stock Medicines</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockMedicines as $medicine)
                    <tr>
                        <td>{{ $medicine->name }}</td>
                        <td class="text-danger">{{ $medicine->stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No low stock medicines</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
