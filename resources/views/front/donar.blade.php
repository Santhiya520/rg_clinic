@extends('front.layouts.app')

@section('title', 'donar Rotary Galaxy Medical Center - Udumalpet')
@section('description', 'donar Rotary Galaxy Medical Center in Udumalpet for OPD/IPD, lab tests, pharmacy, physiotherapy, radiology and emergency care services')
@section('keywords', 'donar medical center Udumalpet, hospital donar, emergency numbers, appointment booking, healthcare donar')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Our Donar</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Our Donar</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

<div class="our-blog">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-9">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">our supporters</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        <span>Meet</span> Our Valuable Donors
                    </h2>
                </div>
                <!-- Section Title End -->
            </div>

        </div>

        <div class="row">


            @foreach($donors as $index => $donor)
                <div class="col-lg-4 col-md-6">
                    <!-- Donor Item Start -->
                    <div class="blog-item wow fadeInUp" data-wow-delay="{{ $index * 0.2 }}s">

                        <div class="post-featured-image" data-cursor-text="View">
                            <figure>
                                <a href="#" class="image-anime">
                                    <img src="{{ asset($donor->image) }}"
                                        alt="{{ $donor->name }}">
                                </a>
                            </figure>
                        </div>

                        <div class="post-item-content">

                            <div class="post-item-body">
                                <span class="donor-category">{{ $donor->category }}</span>
                                <h2><a href="#">{{ $donor->name }}</a></h2>
                                <p>{{ $donor->description }}</p>
                            </div>

                        </div>

                    </div>
                    <!-- Donor Item End -->
                </div>
            @endforeach
        </div>
    </div>
</div>


@endsection
