@extends('layouts.app')

@section('page-title', 'Donor Management')

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
                            <h5 class="title">All Donors</h5>
                            <p>Manage your donors and their information.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.donor.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em> &nbsp; Add New Donor
                            </a>
                        </div>
                    </div>
                </div>

                @if($donors->isEmpty())
                    <div class="text-center py-5">
                        <em class="icon ni ni-users fs-3"></em>
                        <p class="mt-3">No donors found. Click "Add New Donor" to create one.</p>
                    </div>
                @else
                    <!-- Category Filter -->
                    @if($categories->isNotEmpty())
                    <div class="mb-4">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary btn-sm filter-btn active" data-filter="all">All</button>
                            @foreach($categories as $category)
                                <button class="btn btn-outline-primary btn-sm filter-btn" data-filter="{{ $category }}">
                                    {{ $category }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <table class="datatable-init nowrap table">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th width="80">Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th width="80">Order</th>
                                <th width="100">Status</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donors as $donor)
                                <tr data-category="{{ $donor->category }}">
                                    <td>{{ $donor->id }}</td>
                                    <td>
                                        @if($donor->image)
                                            @php
                                                $extension = pathinfo($donor->image, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(strtolower($extension) == 'svg')
                                                <img src="{{ asset($donor->image) }}"
                                                     alt="{{ $donor->name }}"
                                                     style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px; background: #f5f5f5; padding: 5px;">
                                            @else
                                                <img src="{{ asset($donor->image) }}"
                                                     alt="{{ $donor->name }}"
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            @endif
                                        @else
                                            <div style="width: 60px; height: 60px; background: #f5f5f5; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <em class="icon ni ni-user" style="font-size: 30px; color: #ccc;"></em>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $donor->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $donor->category }}</span>
                                    </td>
                                    <td>
                                        <span title="{{ $donor->description }}">
                                            {{ \Str::limit($donor->description, 80) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $donor->order }}</span>
                                    </td>
                                    <td>
                                        @if($donor->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('website.donor.edit', $donor) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <em class="icon ni ni-edit"></em> Edit
                                            </a>


                                            <form action="{{ route('website.donor.destroy', $donor) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this donor?')">
                                                    <em class="icon ni ni-trash"></em> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Category filter functionality
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        const filter = this.getAttribute('data-filter');
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (filter === 'all' || row.getAttribute('data-category') === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
