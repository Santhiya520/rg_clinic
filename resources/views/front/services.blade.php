@extends('front.layouts.app')

@section('title', 'Our Services - Rotary Galaxy Medical Center Udumalpet')
@section('description', 'Complete healthcare services including OPD/IPD, diagnostic lab, pharmacy, physiotherapy,
    radiology, and emergency care in Udumalpet')
@section('keywords', 'medical services Udumalpet, OPD services, IPD care, diagnostic lab, pharmacy, physiotherapy,
    radiology, emergency care, health checkups')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Our Services</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">services</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Services Start -->
    <div class="page-services">
        <div class="container">
            <div class="row">
                @foreach ($services as $index => $service)
                    <div class="col-lg-3 col-md-6">
                        <!-- Service Item Start -->
                        <div class="service-item wow fadeInUp"
                            @if ($index > 0) data-wow-delay="{{ $index * 0.2 }}s" @endif>
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                @if ($service->image)
                                    <img src="{{ asset($service->image) }}" alt="{{ $service->title }}">
                                @endif
                            </div>
                            <!-- Icon Box End -->

                            <!-- Service Body Start -->
                            <div class="service-body">
                                <h3>{{ $service->title }}</h3>
                                <p>{{ $service->description }}</p>
                            </div>
                            <!-- Service Body End -->

                        </div>
                        <!-- Service Item End -->
                    </div>
                @endforeach
            </div>

            <!-- Cta Infobar Section Start -->
            <div class="cta-infobar wow fadeInUp" data-wow-delay="0.5s">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <!-- Cta Content Start -->
                        <div class="cta-info-content">
                            <div class="icon-box">
                                <img src="{{ asset('front/images/icon-cta.svg') }}" alt="">
                            </div>

                            <div class="cta-content">
                                <h3>need quality healthcare in Udumalpet?</h3>
                                <p>Don't worry! You no longer need to travel to distant cities for expert treatment. Get
                                    comprehensive medical care right here at Rotary Galaxy Medical Center.</p>
                            </div>
                        </div>
                        <!-- Cta Content End -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Cta Appointment Button Start -->
                        <div class="cta-appointment-btn">
                            <a href="tel:7900880078" class="btn-default">Call Now</a>
                            <a href="{{ route('appointment') }}" class="btn-default btn-highlighted">Book Appointment</a>
                        </div>
                        <!-- Cta Appointment Button End -->
                    </div>
                </div>
            </div>
            <!-- Cta Infobar Section End -->
        </div>
    </div>
    <!-- Page Services End -->

    <!-- Client Testimonial Start -->
    <div class="our-testimonial parallaxie">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">patient reviews</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>What</span> Our Patients Say</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Testimonial Slider Start -->
                    <div class="testimonial-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper" data-cursor-text="Drag">
                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-rating">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <div class="testimonial-content">
                                                <p>No need to go to Coimbatore for treatment anymore. Rotary Galaxy Medical
                                                    Center provides complete healthcare in Udumalpet itself. The monthly
                                                    medicine discount really helps save money.</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="{{ asset('front/images/patient-1.jpg') }}" alt="Ramesh Kumar">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>ramesh kumar</h3>
                                                <p>diabetes patient</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-rating">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <div class="testimonial-content">
                                                <p>Excellent physiotherapy service for my back pain. The therapists are very
                                                    professional and the equipment is modern. The free home delivery of
                                                    medicines is very convenient.</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="{{ asset('front/images/patient-2.jpg') }}"
                                                        alt="Sundari Devi">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>sundari devi</h3>
                                                <p>regular medicine customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-rating">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <div class="testimonial-content">
                                                <p>Complete lab tests at affordable rates. Got my full body checkup done
                                                    here. The doctors explained everything clearly and the service was very
                                                    prompt. Highly recommended.</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="{{ asset('front/images/patient-3.jpg') }}"
                                                        alt="Arun Prakash">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>arun prakash</h3>
                                                <p>lab test customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-rating">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <div class="testimonial-content">
                                                <p>24/7 emergency service saved my father during a cardiac emergency. The
                                                    ambulance arrived quickly and the doctors provided immediate care. Thank
                                                    you Rotary Galaxy Medical Center.</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="{{ asset('front/images/patient-4.jpg') }}"
                                                        alt="Geetha Ravi">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>geetha ravi</h3>
                                                <p>emergency care patient</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <!-- Testimonial Slider End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Client Testimonial End -->

    <!-- Why Choose Us Start -->
    <div class="why-choose-us">
        <div class="container">
            <div class="row section-row">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">why choose us</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Your Trusted</span> Healthcare Partner in
                        Udumalpet</h2>
                </div>
                <!-- Section Title End -->
            </div>

            <!-- Why Choose Us Box Start -->
            <div class="why-choose-us-box">
                <div class="row no-gutters align-items-center">
                    <div class="col-lg-6">
                        <!-- Why Choose Box Start -->
                        <div class="why-choose-box-1">
                            <!-- Why Choose Item Start -->
                            <div class="why-choose-item wow fadeInUp">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-why-us-1.svg') }}" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Why Choose Content Start -->
                                <div class="why-choose-content">
                                    <h3>expert medical team</h3>
                                    <p>Experienced doctors and specialists available locally for all your healthcare needs
                                        with personalized care.</p>
                                </div>
                                <!-- Why Choose Content End -->
                            </div>
                            <!-- Why Choose Item End -->

                            <!-- Why Choose Item Start -->
                            <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-why-us-2.svg') }}" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Why Choose Content Start -->
                                <div class="why-choose-content">
                                    <h3>comprehensive care</h3>
                                    <p>All healthcare services under one roof - from consultation to diagnostics, treatment
                                        and pharmacy.</p>
                                </div>
                                <!-- Why Choose Content End -->
                            </div>
                            <!-- Why Choose Item End -->

                            <!-- Why Choose Item Start -->
                            <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-why-us-3.svg') }}" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Why Choose Content Start -->
                                <div class="why-choose-content">
                                    <h3>affordable rates</h3>
                                    <p>Special discounts on medicines, health packages and treatments to make healthcare
                                        accessible to all.</p>
                                </div>
                                <!-- Why Choose Content End -->
                            </div>
                            <!-- Why Choose Item End -->
                        </div>
                        <!-- Why Choose Box End -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Why Choose Box Start -->
                        <div class="why-choose-box-2">
                            <!-- Why Choose Item Start -->
                            <div class="why-choose-item wow fadeInUp">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-why-us-4.svg') }}" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Why Choose Content Start -->
                                <div class="why-choose-content">
                                    <h3>advanced equipment</h3>
                                    <p>Modern medical equipment and technology for accurate diagnosis and effective
                                        treatment.</p>
                                </div>
                                <!-- Why Choose Content End -->
                            </div>
                            <!-- Why Choose Item End -->

                            <!-- Why Choose Item Start -->
                            <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-why-us-5.svg') }}" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Why Choose Content Start -->
                                <div class="why-choose-content">
                                    <h3>24/7 availability</h3>
                                    <p>Round-the-clock emergency services, pharmacy and medical support for your peace of
                                        mind.</p>
                                </div>
                                <!-- Why Choose Content End -->
                            </div>
                            <!-- Why Choose Item End -->

                            <!-- Why Choose Item Start -->
                            <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-why-us-6.svg') }}" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Why Choose Content Start -->
                                <div class="why-choose-content">
                                    <h3>family care</h3>
                                    <p>Healthcare services for all age groups - from children to senior citizens with
                                        compassionate care.</p>
                                </div>
                                <!-- Why Choose Content End -->
                            </div>
                            <!-- Why Choose Item End -->
                        </div>
                        <!-- Why Choose Box End -->
                    </div>

                    <div class="col-lg-12">
                        <!-- Why Choose Image Start -->
                        <div class="why-choose-image">
                            <img src="{{ asset('front/images/why-us-img.png') }}"
                                alt="Rotary Galaxy Medical Center Team">
                        </div>
                        <!-- Why Choose Image End -->
                    </div>
                </div>
            </div>
            <!-- Why Choose Us Box End -->
        </div>
    </div>
    <!-- Why Choose Us End -->
@endsection
