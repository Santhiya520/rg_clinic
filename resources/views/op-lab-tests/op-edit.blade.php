@extends('layouts.app')

@section('title', 'Update Lab Test Result')
@section('page-title', 'Update Result - ' . $opLabTest->labTest->name)

@section('content')
<div class="nk-block nk-block-lg">
    <form action="{{ route('op-lab-tests.update', $opLabTest) }}" method="POST">
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
                            Patient: <strong>{{ $opLabTest->opRegister->patient->name }}</strong>
                            ({{ $opLabTest->opRegister->patient->patient_id }})
                        </p>

                        <a href="{{ route('op-lab-tests.show', $opLabTest->op_register_id) }}" class="btn btn-secondary" style="border-radius: 5px">
                            <em class="icon ni ni-arrow-left"></em> &nbsp; Back to Tests
                        </a>
                    </div>
                </div>

                {{-- TEST INFORMATION --}}
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Test Name</label>
                            <input type="text" class="form-control" value="{{ $opLabTest->labTest->name }}" readonly>
                        </div>
                    </div>
                </div>

                {{-- SUB TESTS SECTION --}}
                <div class="mt-4 mb-4">
                    <h6 class="title">Test Parameters</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Test Name</th>
                                    <th width="15%">Unit</th>
                                    <th width="20%">Normal Range</th>
                                    <th width="25%">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opLabTest->subTests as $index => $subTest)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $subTest->test_name }}</td>
                                    <td>{{ $subTest->unit ?? 'N/A' }}</td>
                                    <td>{{ $subTest->normal_range ?? 'N/A' }}</td>
                                    <td>
                                        <input type="text"
                                               name="sub_tests[{{ $subTest->id }}][result]"
                                               class="form-control form-control-sm"
                                               value="{{ old('sub_tests.' . $subTest->id . '.result', $subTest->result) }}"
                                               placeholder="Enter result">
                                        @error('sub_tests.' . $subTest->id . '.result')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- OVERALL RESULT (Optional) --}}
                <div class="form-group">
                    <label class="form-label">Overall Result Summary</label>
                    <textarea class="form-control" name="result" rows="3" placeholder="Enter overall test result summary...">{{ old('result', $opLabTest->result) }}</textarea>
                    <small class="text-muted">Optional: Provide an overall summary of the test results</small>
                    @error('result')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- NOTES --}}
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Enter any notes...">{{ old('notes', $opLabTest->notes) }}</textarea>
                    @error('notes')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select class="form-control" name="status" required>
                        <option value="pending" {{ old('status', $opLabTest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('status', $opLabTest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $opLabTest->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" style="border-radius: 6px 0 0 6px">
                        <em class="icon ni ni-check"></em> &nbsp; Update Result
                    </button>
                    <a href="{{ route('op-lab-tests.show', $opLabTest->op_register_id) }}" class="btn btn-secondary">Cancel</a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
