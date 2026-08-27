@extends('layouts.app')

@section('page-title', 'Edit Team Member')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="card card-preview">
            <div class="card-inner">
                <div class="nk-block-head">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h5 class="title">Edit Team Member #{{ $team->id }}</h5>
                            <p>Update team member information.</p>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('website.team.index') }}" class="btn btn-outline-light" style="border-radius: 5px">
                                <em class="icon ni ni-arrow-left"></em> &nbsp; Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('website.team.update', $team) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $team->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                                <input type="text" name="role" id="role"
                                       class="form-control @error('role') is-invalid @enderror"
                                       value="{{ old('role', $team->role) }}" required>
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Current Image</label>
                                @if($team->image)
                                    @php
                                        $extension = pathinfo($team->image, PATHINFO_EXTENSION);
                                    @endphp
                                    <div class="mb-3">
                                        @if(strtolower($extension) == 'svg')
                                            <img src="{{ asset($team->image) }}"
                                                 alt="{{ $team->name }}"
                                                 style="max-width: 100%; max-height: 200px; border-radius: 8px; background: #f8f9fa; padding: 20px; object-fit: contain; border: 1px solid #e5e5e5;">
                                        @else
                                            <img src="{{ asset($team->image) }}"
                                                 alt="{{ $team->name }}"
                                                 style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e5e5;">
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-3 p-3 text-center" style="background: #f8f9fa; border-radius: 8px; border: 1px dashed #dee2e6;">
                                        <em class="icon ni ni-user" style="font-size: 40px; color: #aaa;"></em>
                                        <p class="mt-2 text-muted">No image uploaded</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="image">New Image</label>
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
                                       value="{{ old('order', $team->order) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>

                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="status"
                                           {{ old('status', $team->status) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Active Status</label>
                                </div>
                                <small class="text-muted">Show/hide this team member</small>
                            </div>

                            <div class="card mt-4" style="background: #f8f9fa;">
                                <div class="card-body">
                                    <h6 class="card-title">Member Information</h6>
                                    <hr class="mt-0">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5">Added:</dt>
                                        <dd class="col-sm-7">{{ $team->created_at->format('M d, Y') }}</dd>

                                        <dt class="col-sm-5">Updated:</dt>
                                        <dd class="col-sm-7">{{ $team->updated_at->format('M d, Y') }}</dd>

                                        <dt class="col-sm-5">Member ID:</dt>
                                        <dd class="col-sm-7">#{{ $team->id }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary" style="border-radius: 5px">
                                    <em class="icon ni ni-save"></em> &nbsp; Update Member
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
