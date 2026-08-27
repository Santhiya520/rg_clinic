@extends('layouts.app')

@section('page-title', 'Notice Board')

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
                            <h5 class="title">Notice Board</h5>
                            <p>Update the notice displayed on the website.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('website.notice.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="description">Notice Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="8"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Enter notice description here..."
                                          required>{{ old('description', $notice->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">This notice will be displayed on the website notice board.</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary" style="border-radius: 5px">
                                    <em class="icon ni ni-save"></em> &nbsp; Update Notice
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Optional: Show last updated info -->
                <div class="mt-4 text-muted small">
                    @if($notice->created_at)
                        Last updated: {{ $notice->updated_at->format('M d, Y h:i A') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
