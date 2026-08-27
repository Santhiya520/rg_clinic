@extends('front.layouts.app')

@section('title', 'gallery Rotary Galaxy Medical Center - Udumalpet')
@section('description',
    'gallery Rotary Galaxy Medical Center in Udumalpet for OPD/IPD, lab tests, pharmacy,
    physiotherapy, radiology and emergency care services')
@section('keywords',
    'gallery medical center Udumalpet, hospital gallery, emergency numbers, appointment booking,
    healthcare gallery')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Gallery</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a target="_blank" href="{{ route('index') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
    <!-- Page Photo Gallery Start -->
    <div class="page-video-gallery">
        <div class="container">
            <div class="row gallery-items">
                @forelse($galleries as $index => $gallery)
                    <div class="col-lg-4 col-md-4">
                        <div class="photo-gallery wow fadeInUp" data-wow-delay="{{ $index * 0.2 }}s"
                            data-cursor-text="View">
                            <a target="_blank" href="{{ asset($gallery->image) }}">
                                <figure>
                                    <img src="{{ asset($gallery->image) }}" alt="Gallery Image {{ $gallery->id }}"
                                        loading="lazy">
                                </figure>
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Fallback static images if no gallery images exist -->
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="col-lg-4 col-md-4">
                            <div class="photo-gallery wow fadeInUp" data-wow-delay="{{ ($i - 1) * 0.2 }}s"
                                data-cursor-text="View">
                                <a target="_blank" href="{{ asset("front/images/video-gallery-img-$i.jpg") }}">
                                    <figure>
                                        <img src="{{ asset("front/images/video-gallery-img-$i.jpg") }}"
                                            alt="Gallery Image {{ $i }}" loading="lazy">
                                    </figure>
                                </a>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
        <!-- Page Photo Gallery End -->



    @endsection
