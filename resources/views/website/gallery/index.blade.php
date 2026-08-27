@extends('layouts.app')

@section('page-title', 'Gallery Management')

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
                            <h5 class="title">All Gallery Images</h5>
                            <p>Manage your gallery images and their display order.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.gallery.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em> &nbsp; Add New Image
                            </a>
                        </div>
                    </div>
                </div>

                @if($galleries->isEmpty())
                    <div class="text-center py-5">
                        <em class="icon ni ni-gallery fs-3"></em>
                        <p class="mt-3">No gallery images found. Click "Add New Image" to create one.</p>
                    </div>
                @else
                    <table class="datatable-init nowrap table">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th width="100">Order</th>
                                <th>Image</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($galleries as $gallery)
                                <tr>
                                    <td>{{ $gallery->id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $gallery->order }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($gallery->image) }}"
                                                 alt="Gallery {{ $gallery->id }}"
                                                 style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('website.gallery.edit', $gallery) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <em class="icon ni ni-edit"></em> Edit
                                            </a>
                                            <form action="{{ route('website.gallery.destroy', $gallery) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this gallery image?')">
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
