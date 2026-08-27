@extends('layouts.app')

@section('title', 'Update IP Radiology Result')
@section('page-title', 'Update Result - ' . $ipRadiology->radiologyTest->name) {{-- Changed variable name --}}

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('radiology.ip.update', $ipRadiology) }}" method="POST" enctype="multipart/form-data"> {{-- Changed --}}
        @csrf
        @method('PUT')

        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

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
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <p class="text-soft mb-0">
                            Patient: <strong>{{ $ipRadiology->inpatientRegister->patient->name }}</strong> {{-- Changed --}}
                            ({{ $ipRadiology->inpatientRegister->patient->patient_id }}) | {{-- Changed --}}
                            IP No: {{ $ipRadiology->inpatientRegister->hospital_ip_no }} {{-- Changed --}}
                        </p>
                        <a href="{{ route('radiology.ip.show', $ipRadiology->inpatient_register_id) }}" {{-- Changed --}}
                           class="btn btn-secondary" style="border-radius: 5px">
                            <em class="icon ni ni-arrow-left"></em>&nbsp; Back to Tests
                        </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Test Name</label>
                            <input type="text" class="form-control" value="{{ $ipRadiology->radiologyTest->name }}" readonly> {{-- Changed --}}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Result</label>
                    <textarea class="form-control" name="result" rows="4" placeholder="Enter test results...">{{ old('result', $ipRadiology->result) }}</textarea> {{-- Changed --}}
                </div>

                <div class="form-group">
                    <label class="form-label">Result Document</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="result_document" id="result_document">
                        <label class="custom-file-label" for="result_document">
                            {{ $ipRadiology->result_document ? 'Change file' : 'Choose file' }} {{-- Changed --}}
                        </label>
                    </div>
                    <small class="form-text text-muted">Allowed: PDF, JPG, JPEG, PNG | Max: 2MB</small>

                    @if($ipRadiology->result_document) {{-- Changed --}}
                        <div class="mt-2">
                            <a href="{{ asset('uploads/radiology-documents/' . $ipRadiology->result_document) }}" {{-- Changed --}}
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <em class="icon ni ni-eye"></em> View Current Document
                            </a>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select class="form-control" name="status" required>
                        <option value="pending" {{ old('status', $ipRadiology->status) == 'pending' ? 'selected' : '' }}>Pending</option> {{-- Changed --}}
                        <option value="completed" {{ old('status', $ipRadiology->status) == 'completed' ? 'selected' : '' }}>Completed</option> {{-- Changed --}}
                        <option value="cancelled" {{ old('status', $ipRadiology->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option> {{-- Changed --}}
                    </select>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                        <em class="icon ni ni-check"></em>&nbsp; Update Result
                    </button>
                    <a href="{{ route('radiology.ip.show', $ipRadiology->inpatient_register_id) }}" {{-- Changed --}}
                       class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('result_document').addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || 'Choose file';
    document.querySelector('.custom-file-label').textContent = fileName;
});
</script>
@endsection
