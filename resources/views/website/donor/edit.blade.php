@extends('layouts.app')

@section('page-title', 'Edit Donor')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="title">Edit Donor #{{ $donor->id }}</h5>
                            <p>Update donor information.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.donor.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('website.donor.update', $donor) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label" for="name">Donor Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $donor->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                                <input type="text" name="category" id="category"
                                       class="form-control @error('category') is-invalid @enderror"
                                       value="{{ old('category', $donor->category) }}" required>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="6"
                                          class="form-control @error('description') is-invalid @enderror"
                                          required>{{ old('description', $donor->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Current Image/Logo</label>
                                @if($donor->image)
                                    @php
                                        $extension = pathinfo($donor->image, PATHINFO_EXTENSION);
                                    @endphp
                                    <div class="mb-3">
                                        @if(strtolower($extension) == 'svg')
                                            <img src="{{ asset($donor->image) }}"
                                                 alt="{{ $donor->name }}"
                                                 style="max-width: 100%; max-height: 200px; border-radius: 8px; background: #f8f9fa; padding: 20px; object-fit: contain; border: 1px solid #e5e5e5;">
                                        @else
                                            <img src="{{ asset($donor->image) }}"
                                                 alt="{{ $donor->name }}"
                                                 style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e5e5;">
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-3 p-3 text-center" style="background: #f8f9fa; border-radius: 8px; border: 1px dashed #dee2e6;">
                                        <em class="icon ni ni-image" style="font-size: 40px; color: #aaa;"></em>
                                        <p class="mt-2 text-muted">No image uploaded</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="image">New Image/Logo</label>
                                <div class="upload-image-box">
                                    <div class="image-preview mb-3" id="imagePreview" style="display: none;">
                                        <img src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                                    </div>
                                    <input type="file" name="image" id="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml,.svg"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">Leave empty to keep current image. Max: 5MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="order">Display Order</label>
                                <input type="number" name="order" id="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       value="{{ old('order', $donor->order) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="status"
                                           {{ old('status', $donor->status) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Active Status</label>
                                </div>
                            </div>

                            <div class="card mt-4" style="background: #f8f9fa;">
                                <div class="card-body">
                                    <h6 class="card-title">Donor Information</h6>
                                    <hr class="mt-0">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5">Added:</dt>
                                        <dd class="col-sm-7">{{ $donor->created_at->format('M d, Y') }}</dd>

                                        <dt class="col-sm-5">Updated:</dt>
                                        <dd class="col-sm-7">{{ $donor->updated_at->format('M d, Y') }}</dd>

                                        <dt class="col-sm-5">Donor ID:</dt>
                                        <dd class="col-sm-7">#{{ $donor->id }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary" style="border-radius: 5px">
                                    <em class="icon ni ni-save"></em> &nbsp; Update Donor
                                </button>
                                <a href="{{ route('website.donor.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
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

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';

            // Special handling for SVG files
            if (file.type === 'image/svg+xml' || file.name.toLowerCase().endsWith('.svg')) {
                previewImg.style.backgroundColor = '#f8f9fa';
                previewImg.style.padding = '20px';
                previewImg.style.objectFit = 'contain';
            } else {
                previewImg.style.backgroundColor = 'transparent';
                previewImg.style.padding = '0';
                previewImg.style.objectFit = 'cover';
            }
        }

        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
