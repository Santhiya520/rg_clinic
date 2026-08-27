@extends('layouts.app')

@section('page-title', 'Medicine Management')

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
                            <a href="{{ route('medicines.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em>&nbsp; Add New Medicine
                            </a>
                        </div>
                    </div>
                </div>
                <table class="datatable-init nowrap table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $medicine)
                            <tr>
                                <td>{{ $medicine->id }}</td>
                                <td>{{ \App\Helpers\StringHelper::decodeQuotes($medicine->name) }}</td>
                                <td>{{ $medicine->category }}</td>
                                <td>
                                    @if($medicine->supplier)
                                        <a href="{{ route('suppliers.edit', $medicine->supplier) }}" class="text-primary">
                                            {{ \App\Helpers\StringHelper::decodeQuotes($medicine->supplier->name) }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>₹{{ $medicine->price }}</td>
                                <td>
                                    <span style="padding:2px 8px"
                                        class="badge @if ($medicine->stock == 0) bg-danger @elseif($medicine->stock <= 10) bg-warning @else bg-success @endif">
                                        {{ $medicine->stock }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $medicine->status == 'active' ? 'success' : ($medicine->status == 'inactive' ? 'warning' : 'danger') }}" style="padding:2px 8px">
                                        {{ ucfirst($medicine->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('medicines.edit', $medicine) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('medicines.destroy', $medicine) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this medicine?')">
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
