@extends('layouts.app')

@section('page-title', 'Operation Successful')

@section('content')
<div class="nk-block nk-block-lg">

    <div class="card card-preview">
        <div class="card-inner">
            <div class="text-center py-5">
                <div class="nk-block-content">
                    <div class="nk-block-content-head text-center">
                        <h5 class="nk-block-title text-success">
                            <em class="icon ni ni-check-circle-fill" style="font-size: 48px;"></em>
                        </h5>
                        <h4 class="title text-success mb-2">{{ session('success') }}</h4>
                        <p class="text-soft">The radiology test operation was completed successfully.</p>
                    </div>
                    <div class="nk-block-content-group">
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <a href="{{ route('radiology-tests.index') }}" class="btn btn-primary p-3">
                                <em class="icon ni ni-medical"></em> View All Tests
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary p-3">
                                <em class="icon ni ni-dashboard"></em> Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
