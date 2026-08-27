@extends('layouts.app')

@section('page-title', 'Add New Team Member')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="title">Add New Team Member</h5>
                            <p>Add a new member to your team.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.team.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('website.team.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                                <input type="text" name="role" id="role"
                                       class="form-control @error('role') is-invalid @enderror"
                                       value="{{ old('role') }}" required
                                       placeholder="e.g., Cardiologist, Senior Doctor, Nurse">
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="image">Profile Image</label>
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
                                    <small class="text-muted">Recommended: Square image (Max: 5MB, JPEG, PNG, JPG, GIF, WEBP, SVG)</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="order">Display Order</label>
                                <input type="number" name="order" id="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       value="{{ old('order', 0) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>

                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="status" checked>
                                    <label class="custom-control-label" for="status">Active Status</label>
                                </div>
                                <small class="text-muted">Show/hide this team member</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary" style="border-radius: 5px">
                                    <em class="icon ni ni-save"></em> &nbsp; Save Team Member
                                </button>
                                <a href="{{ route('website.team.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
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
