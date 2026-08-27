@extends('layouts.app')

@section('page-title', 'Enquiries Management')

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
                            <h5 class="title">All Enquiries</h5>
                            <p>Manage contact form submissions.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <span class="badge bg-info">Unread: {{ $unreadCount }}</span>
                        </div>
                    </div>
                </div>

                @if($enquiries->isEmpty())
                    <div class="text-center py-5">
                        <em class="icon ni ni-inbox fs-3"></em>
                        <p class="mt-3">No enquiries found.</p>
                    </div>
                @else
                    <table class="datatable-init nowrap table">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th width="100">Date</th>
                                <th width="100">Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enquiries as $enquiry)
                                <tr class="{{ !$enquiry->is_read ? 'fw-bold' : '' }}">
                                    <td>{{ $enquiry->id }}</td>
                                    <td>{{ $enquiry->name }}</td>
                                    <td>{{ $enquiry->email }}</td>
                                    <td>{{ $enquiry->phone }}</td>
                                    <td>{{ \Str::limit($enquiry->subject, 30) }}</td>
                                    <td>{{ $enquiry->formatted_date }}</td>
                                    <td>
                                        @if(!$enquiry->is_read)
                                            <span class="badge bg-warning">Unread</span>
                                        @elseif($enquiry->is_replied)
                                            <span class="badge bg-success">Replied</span>
                                        @else
                                            <span class="badge bg-info">Read</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('website.enquiry.show', $enquiry) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <em class="icon ni ni-eye"></em> View
                                            </a>

                                            <form action="{{ route('website.enquiry.destroy', $enquiry) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this enquiry?')">
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
