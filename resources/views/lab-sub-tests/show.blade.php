// resources/views/lab-sub-tests/show.blade.php
@extends('layouts.app')

@section('page-title', 'View Lab Sub Tests')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <div class="preview-block">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Lab Test: <strong>{{ $labTest->name }}</strong></h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('lab-sub-tests.edit', $labTest) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('lab-sub-tests.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sub Test Name</th>
                            <th>Unit</th>
                            <th>Normal Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labTest->subTests as $index => $subTest)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subTest->name }}</td>
                            <td>{{ $subTest->unit ?? 'N/A' }}</td>
                            <td>{{ $subTest->normal_range ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
