@extends('layouts.app')

@section('page-title', 'Admission Successful')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner text-center">
            <div class="nk-block-content">
                <div class="my-4">
                    <em class="icon ni ni-check-circle-fill text-success" style="font-size: 5rem;"></em>
                </div>
                <h4 class="nk-block-title">Patient Admitted Successfully!</h4>
                <p class="text-soft">
                    @if(session('success'))
                        {{ session('success') }}
                    @else
                        The patient has been successfully admitted to the hospital.
                    @endif
                </p>

                <div class="mt-4">
                    <a href="{{ route('inpatient-register.index') }}" class="btn btn-primary">
                        <em class="icon ni ni-list"></em> View All Inpatients
                    </a>
                    <a href="{{ route('inpatient-register.create') }}" class="btn btn-secondary">
                        <em class="icon ni ni-plus"></em> Admit Another Patient
                    </a>
                    <a href="{{ route('inpatient-register.doctor-ip') }}" class="btn btn-info">
                        <em class="icon ni ni-stethoscope"></em> Go to Doctor IP
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
