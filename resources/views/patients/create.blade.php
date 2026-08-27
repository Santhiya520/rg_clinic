@extends('layouts.app')

@section('page-title', 'Register New Patient')

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
                                <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Patients
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('patients.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required placeholder="Enter patient's full name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}"
                                    placeholder="Enter mobile number">
                                @error('mobile')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Age *</label>
                                <input type="number" class="form-control" name="age" value="{{ old('age') }}"
                                    required min="0" max="150" placeholder="Age">
                                @error('age')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Sex *</label>
                                <select class="form-control" name="sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('sex') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="address" rows="1" required placeholder="Enter complete address">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">Register Patient</button>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
