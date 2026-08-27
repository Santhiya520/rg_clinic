@extends('layouts.app')

@section('page-title', 'Service Management')

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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
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
                            <h5 class="title">All Services</h5>
                            <p>Manage your services and their display order.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.service.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em> &nbsp; Add New Service
                            </a>
                        </div>
                    </div>
                </div>

                @if($services->isEmpty())
                    <div class="text-center py-5">
                        <em class="icon ni ni-service fs-3"></em>
                        <p class="mt-3">No services found. Click "Add New Service" to create one.</p>
                    </div>
                @else
                    <table class="datatable-init nowrap table">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th width="80">Image</th>
                                <th>Title</th>
                                <th style="width: 20%">Description</th>
                                <th width="80">Order</th>
                                <th width="100">Status</th>
                                <th width="250">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>
                                        @if($service->image)
                                            @php
                                                $extension = pathinfo($service->image, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(strtolower($extension) == 'svg')
                                                <img src="{{ asset($service->image) }}"
                                                     alt="{{ $service->title }}"
                                                     style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px; background: #f5f5f5; padding: 5px;">
                                            @else
                                                <img src="{{ asset($service->image) }}"
                                                     alt="{{ $service->title }}"
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            @endif
                                        @else
                                            <div style="width: 60px; height: 60px; background: #f5f5f5; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <em class="icon ni ni-image" style="font-size: 20px; color: #ccc;"></em>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $service->title }}</strong>
                                    </td>
                                    <td style="width: 200px">
                                        @if($service->description)
                                            <span title="{{ $service->description }}">
                                                {{ $service->short_description }}
                                            </span>
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $service->order }}</span>
                                    </td>
                                    <td>
                                        @if($service->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('website.service.edit', $service) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <em class="icon ni ni-edit"></em> Edit
                                            </a>

                                            <form action="{{ route('website.service.destroy', $service) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this service?')">
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
