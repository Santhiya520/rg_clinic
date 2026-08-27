@extends('layouts.app')

@section('page-title', 'Edit Patient')

@section('content')
    <div class="nk-block nk-block-lg">

        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('patients.index') }}" class="btn btn-secondary" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Patients
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('patients.update', $patient) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $patient->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile"
                                    value="{{ old('mobile', $patient->mobile) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Age *</label>
                                <input type="number" class="form-control" name="age"
                                    value="{{ old('age', $patient->age) }}" required min="0" max="150">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Sex *</label>
                                <select class="form-control" name="sex" required>
                                    <option value="male" {{ old('sex', $patient->sex) == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ old('sex', $patient->sex) == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                    <option value="other" {{ old('sex', $patient->sex) == 'other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="address" rows="1" required>{{ old('address', $patient->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">Update Patient</button>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary" style="border-radius:0 6px 6px 0">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
