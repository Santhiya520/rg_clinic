@extends('layouts.app')

@section('page-title', 'Review Management')

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
                            <h5 class="title">All Reviews</h5>
                            <p>Manage customer reviews and ratings.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.review.create') }}" class="btn btn-primary" style="border-radius: 5px">
                                <em class="icon ni ni-plus"></em> &nbsp; Add New Review
                            </a>
                        </div>
                    </div>
                </div>

                @if($reviews->isEmpty())
                    <div class="text-center py-5">
                        <em class="icon ni ni-star fs-3"></em>
                        <p class="mt-3">No reviews found. Click "Add New Review" to create one.</p>
                    </div>
                @else
                    <table class="datatable-init nowrap table">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Name</th>
                                <th>Review</th>
                                <th width="120">Star Rating</th>
                                <th width="80">Order</th>
                                <th width="100">Status</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>
                                        <strong>{{ $review->name }}</strong>
                                    </td>
                                    <td>
                                        <span title="{{ $review->review }}">
                                            {{ \Str::limit($review->review, 80) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="star-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->star_count)
                                                    <em class="icon ni ni-star-fill text-warning"></em>
                                                @else
                                                    <em class="icon ni ni-star text-muted"></em>
                                                @endif
                                            @endfor
                                            <span class="badge bg-info ms-2">{{ $review->star_count }}/5</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $review->order }}</span>
                                    </td>
                                    <td>
                                        @if($review->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('website.review.edit', $review) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <em class="icon ni ni-edit"></em> Edit
                                            </a>


                                            <form action="{{ route('website.review.destroy', $review) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this review?')">
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

@push('styles')
<style>
.star-rating {
    display: flex;
    align-items: center;
    gap: 2px;
}
.star-rating .ni-star-fill,
.star-rating .ni-star {
    font-size: 14px;
}
</style>
@endpush
