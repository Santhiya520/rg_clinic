@extends('layouts.app')

@section('title', 'Update Radiology Result')
@section('page-title', 'Update Result - ' . $opRadiology->radiologyTest->name)

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('radiology.op.update', $opRadiology) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR MESSAGES --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-preview">
            <div class="card-inner">

                {{-- PATIENT INFO --}}
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <p class="text-soft mb-0">
                            Patient: <strong>{{ $opRadiology->opRegister->patient->name }}</strong>
                            ({{ $opRadiology->opRegister->patient->patient_id }})
                        </p>

                        <a href="{{ route('radiology.op.show', $opRadiology->op_register_id) }}" class="btn btn-secondary" style="border-radius: 5px">
                            <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Tests
                        </a>
                    </div>
                </div>

                {{-- TEST INFORMATION --}}
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Test Name</label>
                            <input type="text" class="form-control" value="{{ $opRadiology->radiologyTest->name }}" readonly>
                        </div>
                    </div>
                </div>

                {{-- RESULT --}}
                <div class="form-group">
                    <label class="form-label">Result</label>
                    <textarea class="form-control" name="result" rows="4" placeholder="Enter test results...">{{ old('result', $opRadiology->result) }}</textarea>
                    @error('result')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- UPLOAD FILE --}}
                <div class="form-group">
                    <label class="form-label">Result Document</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="result_document" id="result_document">
                        <label class="custom-file-label" for="result_document">
                            {{ $opRadiology->result_document ? 'Change file' : 'Choose file' }}
                        </label>
                    </div>
                    <small class="form-text text-muted">Allowed: PDF, JPG, JPEG, PNG | Max: 2MB</small>
                    @error('result_document')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    {{-- VIEW PREVIOUS DOCUMENT --}}
                    @if($opRadiology->result_document)
                        <div class="mt-2">
                            <a href="{{ asset('uploads/radiology-documents/' . $opRadiology->result_document) }}"
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <em class="icon ni ni-eye"></em> View Current Document
                            </a>
                            <span class="text-muted ml-2">{{ $opRadiology->result_document }}</span>
                        </div>
                    @endif
                </div>

                {{-- PAYMENT & STATUS --}}
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select class="form-control" name="status" required>
                                <option value="pending" {{ old('status', $opRadiology->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status', $opRadiology->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $opRadiology->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                        <em class="icon ni ni-check"></em>&nbsp; Update Result
                    </button>
                    <a href="{{ route('radiology.op.show', $opRadiology->op_register_id) }}" class="btn btn-secondary">Cancel</a>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- Change label when selecting file --}}
<script>
document.getElementById('result_document').addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || 'Choose file';
    document.querySelector('.custom-file-label').textContent = fileName;
});
</script>
@endsection
