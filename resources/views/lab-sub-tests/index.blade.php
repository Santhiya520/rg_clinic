@extends('layouts.app')

@section('page-title', 'Lab Sub Tests Management')

@section('content')
<div class="nk-block nk-block-lg">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-preview">
        <div class="card-inner">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="title">Lab Test Sub Tests</h6>
                <a href="{{ route('lab-sub-tests.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Sub Tests
                </a>
            </div>

            <!-- Temporary: Remove datatable class -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Lab Test Name</th>
                        <th>Sub Tests Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($labTests as $labTest)
                    <tr>
                        <td>
                            {{ $labTest->name }}<br>
                            <small class="text-muted">ID: {{ $labTest->id }}</small>
                        </td>
                        <td>
                            <strong>{{ $labTest->subTests->count() }}</strong>
                        </td>
                        <td>
                            <div class="btn-group">
                                @if($labTest->subTests->count() > 0)
                                <a href="{{ route('lab-sub-tests.show', $labTest) }}" class="btn btn-sm btn-outline-primary" title="View Sub Tests">
                                    <i class="fas fa-eye"></i> Show
                                </a>
                                <a href="{{ route('lab-sub-tests.edit', $labTest) }}" class="btn btn-sm btn-outline-info" title="Edit Sub Tests">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('lab-sub-tests.destroy', $labTest) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete all sub tests?')" title="Delete Sub Tests">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">No sub tests</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
