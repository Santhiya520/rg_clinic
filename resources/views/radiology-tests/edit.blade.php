@extends('layouts.app')

@section('page-title', 'Edit Radiology Test')

@section('content')
    <div class="nk-block nk-block-lg">

        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('radiology-tests.index') }}" class="btn btn-secondary" style="border-radius: 5px" >
                                <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Tests
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('radiology-tests.update', $radiologyTest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $radiologyTest->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Price (₹) *</label>
                                <input type="number" step="0.01" class="form-control" name="price"
                                    value="{{ old('price', $radiologyTest->price) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $radiologyTest->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select class="form-control" name="status" required>
                            <option value="active"
                                {{ old('status', $radiologyTest->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive"
                                {{ old('status', $radiologyTest->status) == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="border-radius: 5px">Update Test</button>
                        <a href="{{ route('radiology-tests.index') }}" class="btn btn-secondary" style="border-radius: 5px">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
