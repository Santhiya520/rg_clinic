@extends('layouts.app')

@section('page-title', 'Edit Review')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="title">Edit Review #{{ $review->id }}</h5>
                            <p>Update customer review information.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.review.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('website.review.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label" for="name">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $review->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="review">Review <span class="text-danger">*</span></label>
                                <textarea name="review" id="review" rows="6"
                                          class="form-control @error('review') is-invalid @enderror"
                                          required>{{ old('review', $review->review) }}</textarea>
                                @error('review')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="star_count">Star Rating <span class="text-danger">*</span></label>
                                <select name="star_count" id="star_count"
                                        class="form-control @error('star_count') is-invalid @enderror" required>
                                    <option value="">Select Rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('star_count', $review->star_count) == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ Str::plural('Star', $i) }}
                                        </option>
                                    @endfor
                                </select>
                                @error('star_count')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <label class="form-label" for="order">Display Order</label>
                                <input type="number" name="order" id="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       value="{{ old('order', $review->order) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="status"
                                           {{ old('status', $review->status) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Active Status</label>
                                </div>
                            </div>

                            <div class="card mt-4" style="background: #f8f9fa;">
                                <div class="card-body">
                                    <h6 class="card-title">Review Information</h6>
                                    <hr class="mt-0">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5">Created:</dt>
                                        <dd class="col-sm-7">{{ $review->created_at->format('M d, Y') }}</dd>

                                        <dt class="col-sm-5">Updated:</dt>
                                        <dd class="col-sm-7">{{ $review->updated_at->format('M d, Y') }}</dd>

                                        <dt class="col-sm-5">Review ID:</dt>
                                        <dd class="col-sm-7">#{{ $review->id }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary" style="border-radius: 5px">
                                    <em class="icon ni ni-save"></em> &nbsp; Update Review
                                </button>
                                <a href="{{ route('website.review.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
