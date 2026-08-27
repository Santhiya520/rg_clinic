@extends('layouts.app')

@section('page-title', 'Edit Slider')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="title">Edit Slider #{{ $slider->id }}</h5>
                            <p>Update the slider image and order.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.slider.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('website.slider.update', $slider) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Current Image</label>
                                <div class="mb-3">
                                    <img src="{{ asset($slider->image) }}"
                                         alt="Current Slider"
                                         style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="image">New Image</label>
                                <div class="upload-image-box">
                                    <div class="image-preview mb-3" id="imagePreview" style="display: none;">
                                        <img src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                                    </div>
                                    <input type="file" name="image" id="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept="image/jpeg,image/png,image/jpg,image/gif"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">Leave empty to keep current image. Max: 2MB, JPEG, PNG, JPG, GIF</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="order">Display Order</label>
                                <input type="number" name="order" id="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       value="{{ old('order', $slider->order) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary" style="border-radius: 5px">
                                    <em class="icon ni ni-save"></em> &nbsp; Update Slider
                                </button>
                                <a href="{{ route('website.slider.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
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
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
