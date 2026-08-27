@extends('layouts.app')

@section('page-title', 'Enquiry Details')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="title">Enquiry #{{ $enquiry->id }}</h5>
                            <p>View enquiry details.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.enquiry.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Enquiry Information</h5>
                                <hr>

                                <div class="mb-3">
                                    <strong>Name:</strong>
                                    <p class="mb-0">{{ $enquiry->name }}</p>
                                </div>

                                <div class="mb-3">
                                    <strong>Email:</strong>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Phone:</strong>
                                    <p class="mb-0">
                                        <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Subject:</strong>
                                    <p class="mb-0">{{ $enquiry->subject }}</p>
                                </div>

                                <div class="mb-3">
                                    <strong>Message:</strong>
                                    <p class="mb-0">{{ $enquiry->message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Status</h5>
                                <hr>

                                <div class="mb-3">
                                    <strong>Received:</strong>
                                    <p>{{ $enquiry->formatted_date }}</p>
                                </div>

                                <div class="mb-3">
                                    <strong>Status:</strong>
                                    <p>
                                        @if(!$enquiry->is_read)
                                            <span class="badge bg-warning">Unread</span>
                                        @elseif($enquiry->is_replied)
                                            <span class="badge bg-success">Replied</span>
                                        @else
                                            <span class="badge bg-info">Read</span>
                                        @endif
                                    </p>
                                </div>

                                <hr>

                                <div class="d-grid gap-2">
                                    @if(!$enquiry->is_replied)
                                        <form action="{{ route('website.enquiry.replied', $enquiry) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success w-100 mb-2">
                                                <em class="icon ni ni-check"></em> Mark as Replied
                                            </button>
                                        </form>
                                    @endif

                                    @if($enquiry->is_read)
                                        <form action="{{ route('website.enquiry.unread', $enquiry) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning w-100 mb-2">
                                                <em class="icon ni ni-undo"></em> Mark as Unread
                                            </button>
                                        </form>
                                    @endif

                                    <a href="mailto:{{ $enquiry->email }}?subject=Re: {{ $enquiry->subject }}" class="btn btn-primary w-100 mb-2">
                                        <em class="icon ni ni-mail"></em> Reply via Email
                                    </a>

                                    <a href="tel:{{ $enquiry->phone }}" class="btn btn-info w-100 mb-2">
                                        <em class="icon ni ni-call"></em> Call Now
                                    </a>

                                    <form action="{{ route('website.enquiry.destroy', $enquiry) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100"
                                            onclick="return confirm('Are you sure you want to delete this enquiry?')">
                                            <em class="icon ni ni-trash"></em> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
