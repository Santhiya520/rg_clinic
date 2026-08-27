@extends('layouts.app')

@section('page-title', 'Radiology Tests Management')

@section('content')
    <div class="nk-block nk-block-lg">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-bs-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('radiology-tests.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em> &nbsp; Add New Test
                            </a>
                        </div>
                    </div>
                </div>
                <table class="datatable-init nowrap table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tests as $test)
                            <tr>
                                <td>{{ $test->id }}</td>
                                <td>{{ $test->name }}</td>
                                <td>{{ Str::limit($test->description, 50) }}</td>
                                <td>₹{{ $test->price }}</td>
                                <td>
                                    <span style="padding:2px 8px" class="badge bg-{{ $test->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($test->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('radiology-tests.edit', $test) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('radiology-tests.destroy', $test) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this test?')">
                                                Delete
                                            </button>
                                        </form>
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
