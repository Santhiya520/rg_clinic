@extends('front.layouts.app')

@section('title', 'Physiocare - Physiotherapy HTML Template')
@section('description', 'Quality physiotherapy services for pain relief and wellness')
@section('keywords', 'physiotherapy, pain relief, therapy, wellness, rehabilitation')

@section('content')
    <!-- Hero Section Start -->
    {{-- <div class="hero bg-image parallaxie">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Hero Content Start -->
                    <div class="hero-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Welcome to rotary galaxy medical center</h3>
                            <h1 class="text-anime-style-2" data-cursor="-opaque"><span>complete healthcare</span> solutions
                                under one roof</h1>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">Rotary Galaxy Medical Centre is a dedicated healthcare institution committed to providing high-quality and affordable medical services to the people of the Udumalpet region.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Hero Content Body Start -->
                        <div class="hero-content-body wow fadeInUp" data-wow-delay="0.5s">
                            <a href="{{ route('services') }}" class="btn-default">explore services</a>
                            <a href="{{ route('patient.login') }}" class="btn-default btn-highlighted">book appointment</a>
                        </div>
                        <!-- Hero Content Body End -->
                    </div>
                    <!-- Hero Content End -->
                </div>
            </div>
        </div>
    </div> --}}
    @if ($notice && $notice->description)
        <marquee class="bg-info" behavior="scroll" direction="left" scrollamount="6">
            {{ $notice->description }}
        </marquee>
    @endif
    <div class="hero bg-image hero-slider">
        <div class="hero-slider-layout">
            <div class="swiper">
                <div class="swiper-wrapper" style="height: 800px">
                    @forelse($sliders as $slider)
                        <!-- Hero Slide Start -->
                        <div class="swiper-slide">
                            <div class="hero-slide">
                                <!-- Slider Image Start -->
                                <div class="hero-slider-image">
                                    <img src="{{ asset($slider->image) }}">
                                </div>
                                <!-- Slider Image End -->
                            </div>
                        </div>
                        <!-- Hero Slide End -->
                    @empty
                        <!-- Fallback if no sliders in database -->
                        <div class="swiper-slide">
                            <div class="hero-slide">
                                <div class="hero-slider-image">
                                    <img src="{{ asset('front/images/hero-bg.jpg') }}">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->
    <!-- Home Contact Us Start -->
    <div class="home-contact-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Contact Item Start -->
                    <div class="home-contact-item wow fadeInUp">
                        <!-- Icon Box Start -->
                        <div class="icon-box" style="background-color: white;">
                            <img src="{{ asset('front/images/icon-home-contact-us-1.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Home Contact Content Start -->
                        <div class="home-contact-content">
                            <h3>expert medical team</h3>
                            <p>Experienced doctors, specialists, and trained medical staff for comprehensive care</p>
                        </div>
                        <!-- Home Contact Content End -->
                    </div>
                    <!-- Contact Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Contact Item Start -->
                    <div class="home-contact-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Icon Box Start -->
                        <div class="icon-box" style="background-color: white;">
                            <img src="{{ asset('front/images/icon-home-contact-us-2.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Home Contact Content Start -->
                        <div class="home-contact-content">
                            <h3>24/7 emergency</h3>
                            <p>Round-the-clock emergency services with ambulance facility and critical care</p>
                        </div>
                        <!-- Home Contact Content End -->
                    </div>
                    <!-- Contact Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Contact Item Start -->
                    <div class="home-contact-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Icon Box Start -->
                        <div class="icon-box" style="background-color: white;">
                            <img src="{{ asset('front/images/icon-home-contact-us-3.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Home Contact Content Start -->
                        <div class="home-contact-content">
                            <h3>pharmacy</h3>
                            <p>Well-stocked pharmacy with monthly medicine delivery and special discounts</p>
                        </div>
                        <!-- Home Contact Content End -->
                    </div>
                    <!-- Contact Item End -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Contact Item Start -->
                    <div class="home-contact-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Icon Box Start -->
                        <div class="icon-box" style="background-color: white;">
                            <img src="{{ asset('front/images/icon-service-1.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Home Contact Content Start -->
                        <div class="home-contact-content">
                            <h3>home visit</h3>
                            <p>Expert doctors and nurses visiting patients at their homes for convenient care</p>
                        </div>
                        <!-- Home Contact Content End -->
                    </div>
                    <!-- Contact Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Home Contact Us End -->

    <!-- About Us Start -->
    <div class="about-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- About Image Start -->
                    <div class="about-us-image mb-5">
                        <div class="about-img">
                            <figure class="reveal image-anime">
                                <img src="{{ asset('front/images/about-img.jpeg') }}" alt="Rotary Galaxy Medical Center">
                            </figure>

                            <!-- Company Experience Box Start -->
                            <div class="company-experience" style="z-index: 9;">
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-experience.svg') }}" alt="">
                                </div>
                                <div class="company-experience-content">
                                    <h3><span class="counter">1</span>+</h3>
                                    <p>year of trust</p>
                                </div>
                            </div>
                            <!-- Company Experience Box End -->
                        </div>
                    </div>
                    <img src="{{ asset('front/images/van.jpg') }}" alt="" style="position: relative;margin-top:-17% !important;width: 300px;float: right;left: -5%;border-radius: 30px;">
                    <!-- About Image End -->
                    <div class="contact-info mt-5 wow fadeInUp" data-wow-delay="1s">
                        <div class="contact-info-item">
                            <div class="contact-info-content">
                                <h3>Contact Us</h3>
                                <p>Rotary Galaxy Medical Centre,<br>
                                    Shankar Nagar, Dharapuram Road,<br>
                                    Udumalpet.
                                    <br> Chairman V.Ponrraaj
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- About Us Content Start -->
                    <div class="about-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">about our center</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Rotary Galaxy Medical Centre
                            </h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">Rotary Galaxy Medical Centre is a dedicated
                                healthcare institution committed to providing high-quality and affordable medical services
                                to the people of the Udumalpet region.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- About Us Body Start -->
                        <div class="about-us-body">
                            <!-- About List Item Start -->
                            <div class="about-list-item wow fadeInUp" data-wow-delay="0.5s">
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-about-us-1.svg') }}" alt="">
                                </div>
                                <div class="about-list-content">
                                    <h3>OPD & IPD Services</h3>
                                    <p>Outpatient and Inpatient care for all medical conditions</p>
                                </div>
                            </div>
                            <!-- About List Item End -->

                            <!-- About List Item Start -->
                            <div class="about-list-item wow fadeInUp" data-wow-delay="0.5s">
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-about-us-2.svg') }}" alt="">
                                </div>
                                <div class="about-list-content">
                                    <h3>Physiotherapy Unit</h3>
                                    <p>Modern equipment and expert therapists for rehabilitation</p>
                                </div>
                            </div>
                            <!-- About List Item End -->

                            <!-- About List Item Start -->
                            <div class="about-list-item wow fadeInUp" data-wow-delay="0.75s">
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-about-us-3.svg') }}" alt="">
                                </div>
                                <div class="about-list-content">
                                    <h3>Proposed Radiology & Imaging</h3>
                                    <p>X-ray, Ultrasound and other diagnostic imaging services</p>
                                </div>
                            </div>
                            <!-- About List Item End -->

                            <!-- About List Item Start -->
                            <div class="about-list-item wow fadeInUp" data-wow-delay="0.75s">
                                <div class="icon-box">
                                    <img src="{{ asset('front/images/icon-about-us-4.svg') }}" alt="">
                                </div>
                                <div class="about-list-content">
                                    <h3>Ambulance Service</h3>
                                    <p>24/7 emergency ambulance with basic life support</p>
                                </div>
                            </div>
                            <!-- About List Item End -->
                        </div>
                        <!-- About Us Body End -->

                        <!-- About Us Footer Start -->
                        <div class="about-us-footer">
                            <!-- Contact Info Start -->
                            <div class="contact-info wow fadeInUp" data-wow-delay="1s">
                                <div class="contact-info-item">
                                    <div class="contact-info-content">
                                        <h3>emergency numbers</h3>
                                        <p>79 00 88 00 78 | 79 00 65 00 78</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Contact Info End -->

                            <!-- Appointment Button Start -->
                            <div class="appointment-btn wow fadeInUp" data-wow-delay="1s">
                                <a href="tel:7900880078" class="btn-default">Call Now</a>
                                <a href="{{ route('patient.login') }}" class="btn-default btn-highlighted">Book
                                    Appointment</a>
                            </div>
                            <!-- Appointment Button End -->
                        </div>
                        <!-- About Us Footer End -->
                    </div>
                    <!-- About Us Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- About Us End -->

    <div class="page-service-single mt-0 pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="service-single-content">
                        <div class="service-featured-img">
                            <figure class="reveal image-anime">
                                <img src="{{ asset('front/images/journey.jpeg') }}" alt="">
                            </figure>
                        </div>
                        <div class="service-entry">
                            <h3 class="wow fadeInUp">Our Journey</h3>

                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                <strong>The Beginning:</strong> Our medical service commenced on May 29, 2025, operating
                                initially from the first floor of a private hospital.
                            </p>

                            <p class="wow fadeInUp" data-wow-delay="0.4s">
                                <strong>Expansion:</strong> Encouraged by the overwhelming support of the community, we
                                moved to our current facility on November 9, 2025, located at Shankar Nagar, Dharapuram
                                Road, Udumalpet, offering enhanced facilities.
                            </p>

                            <h3 class="wow fadeInUp" data-wow-delay="0.6s">Key Features & Facilities</h3>

                            <p class="wow fadeInUp" data-wow-delay="0.8s">
                                We provide essential healthcare services under one roof to ensure convenience and quality
                                care for all our patients.
                            </p>

                            <ul class="wow fadeInUp" data-wow-delay="1s">
                                <li><strong>Pharmacy:</strong> Well-stocked with quality medicines.</li>
                                <li><strong>Laboratory:</strong> Equipped with modern technology for accurate blood tests
                                    and diagnostics.</li>
                                <li><strong>Physiotherapy:</strong> Specialized physical therapy services to aid in recovery
                                    and mobility.</li>
                            </ul>

                            <h3 class="wow fadeInUp" data-wow-delay="1.2s">Healthcare at Your Doorstep</h3>

                            <p class="wow fadeInUp" data-wow-delay="1.4s">
                                Understanding the challenges faced by elderly and bedridden patients, we proudly offer a
                                unique <strong>Home Visit Medical Service</strong>, ensuring medical care reaches patients
                                in the comfort of their homes.
                            </p>

                            <p class="wow fadeInUp" data-wow-delay="1.6s">
                                <strong>Special Mention:</strong> To support this noble initiative, Chennai Mission has
                                generously donated a specialized Medical Van. This contribution enables us to extend our
                                services efficiently and reach more patients in need. We express our heartfelt gratitude for
                                their support.
                            </p>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="service-sidebar">

                        <div class="service-catagery-list wow fadeInUp">
                            <h3>our services</h3>
                            <ul>
                                <li><a href="#">Pharmacy</a></li>
                                <li><a href="#">Laboratory</a></li>
                                <li><a href="#">Physiotherapy</a></li>
                                <li><a href="#">Home Visit Service</a></li>
                            </ul>
                        </div>

                        <div class="sidebar-cta-box wow fadeInUp" data-wow-delay="0.5s">

                            <div class="cta-content">
                                <h3>Your Health, Our Priority</h3>
                                <p>Contact us today to access quality healthcare services and compassionate medical support.
                                </p>
                            </div>

                            <div class="cta-appointment-btn">
                                <a href="{{ route('contact') }}" class="btn-default">Contact Us</a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Mission Vision Start -->
    <div class="mission-vision pt-0 mt-0">
        <div class="container">
            <div class="row section-row">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">our commitment</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Quality Healthcare</span> & Excellence in
                        Service</h2>
                </div>
                <!-- Section Title End -->
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <!-- Mva Item Start -->
                    <div class="our-mva-item wow fadeInUp">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-our-mission.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Mva Content Start -->
                        <div class="mva-item-content">
                            <h3>our mission</h3>
                            <p>To provide comprehensive, affordable, and accessible healthcare services to the people of
                                Udumalpet and surrounding areas, ensuring quality medical care, timely diagnosis, and
                                effective treatment for all.</p>
                        </div>
                        <!-- Mva Content End -->
                    </div>
                    <!-- Mva Item End -->
                </div>

                <div class="col-lg-4 col-md-6">
                    <!-- Mva Item Start -->
                    <div class="our-mva-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-our-vision.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Mva Content Start -->
                        <div class="mva-item-content">
                            <h3>our vision</h3>
                            <p>To build a healthier community and serve humanity with compassion and become the most trusted
                                and preferred healthcare provider in the region, known for
                                excellence in medical services, patient care, and community health improvement through
                                advanced facilities and compassionate service.</p>
                        </div>
                        <!-- Mva Content End -->
                    </div>
                    <!-- Mva Item End -->
                </div>

                <div class="col-lg-4 col-md-6">
                    <!-- Mva Item Start -->
                    <div class="our-mva-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-our-approch.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Mva Content Start -->
                        <div class="mva-item-content">
                            <h3>Our Background</h3>
                            <p>Our medical centre is proudly managed by the Rotary Udumalpet Galaxy Trust, an initiative of
                                the Rotary Club of Udumalpet Galaxy. Driven by the core value of "Service Above Self," the
                                hospital operates under the able leadership of its Our Mentor, Rtn.Adv.V. Ponrraaj .
                                A significant pillar of strength and a key driving force behind the establishment and vision
                                of this hospital is Rtn. Hari Narayanan, whose guidance continues to be instrumental in our
                                mission.</p>
                        </div>
                        <!-- Mva Content End -->
                    </div>
                    <!-- Mva Item End -->
                </div>
            </div>

            <!-- Call To Action Start -->
            <div class="cta-infobar wow fadeInUp" data-wow-delay="0.75s">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <!-- Cta Content Start -->
                        <div class="cta-info-content">
                            <div class="icon-box">
                                <img src="{{ asset('front/images/icon-cta.svg') }}" alt="">
                            </div>

                            <div class="cta-content">
                                <h3>need quality healthcare in Udumalpet?</h3>
                                <p>Contact us today to schedule your consultation and take the first step towards better
                                    health. No need to travel to distant cities for expert treatment.</p>
                            </div>
                        </div>
                        <!-- Cta Content End -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Cta Appointment Button Start -->
                        <div class="cta-appointment-btn">
                            <a href="{{ route('patient.login') }}" class="btn-default">book appointment</a>
                            <a href="tel:7900880078" class="btn-default btn-highlighted">call now</a>
                        </div>
                        <!-- Cta Appointment Button End -->
                    </div>
                </div>
            </div>
            <!-- Call To Action End -->
        </div>
    </div>
    <!-- Mission Vision End -->

    <div class="page-single-post pt-0 mt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="post-image">
                        <figure class="image-anime reveal">
                            <img src="{{ asset('front/images/journey1.jpeg') }}" alt="">
                        </figure>
                    </div>

                    <div class="post-content">
                        <div class="post-entry">

                            <h2 class="text-anime-style-2">Why Choose Us?</h2>

                            <p class="wow fadeInUp">
                                We are committed to delivering quality healthcare services that are accessible, affordable,
                                and patient-focused. Our approach is built on trust, compassion, and a strong dedication to
                                community well-being.
                            </p>

                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                <strong>Affordable Excellence:</strong> All medical consultations and laboratory tests are
                                provided at subsidized and affordable rates, ensuring that quality healthcare is accessible
                                to people from all sections of society.
                            </p>

                            <blockquote class="wow fadeInUp" data-wow-delay="0.4s">
                                <p>
                                    Our mission is simple — to provide high-quality medical care with compassion,
                                    affordability, and a service-first mindset for every patient.
                                </p>
                            </blockquote>

                            <p class="wow fadeInUp" data-wow-delay="0.6s">
                                <strong>Significant Discounts on Medicine:</strong> To reduce the financial burden on
                                patients, especially those with chronic conditions, we offer maximum discounts on monthly
                                maintenance medications.
                            </p>

                            <p class="wow fadeInUp" data-wow-delay="0.8s">
                                <strong>Service-Oriented Care:</strong> As a trust-run institution, our primary focus is the
                                well-being and recovery of our patients rather than profit. Every service we provide is
                                driven by care and commitment.
                            </p>

                            <ul class="wow fadeInUp" data-wow-delay="1s">
                                <li>Affordable and transparent pricing for all services.</li>
                                <li>Patient-first approach with compassionate care.</li>
                                <li>Special support for elderly and chronic patients.</li>
                                <li>Trusted and community-focused healthcare services.</li>
                                <li>Commitment to quality treatment and patient satisfaction.</li>
                            </ul>

                            <p class="wow fadeInUp" data-wow-delay="1.2s">
                                Our goal is to ensure that every patient receives the care they deserve without financial
                                stress. We continue to work towards building a healthier community through accessible and
                                reliable medical services.
                            </p>

                        </div>

                        <div class="post-tag-links">
                            <div class="row align-items-center">
                                <div class="col-lg-10">
                                    <div class="post-tags wow fadeInUp" data-wow-delay="0.5s">
                                        <span class="tag-links">
                                            Tags:
                                            <a href="#">affordablecare</a>
                                            <a href="#">healthcare</a>
                                            <a href="#">patientcare</a>
                                            <a href="#">communityservice</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="post-social-sharing wow fadeInUp" data-wow-delay="0.5s">
                                        <ul>
                                            <li><a href="https://www.facebook.com/profile.php?id=61585978964724"><i
                                                        class="fa-brands fa-facebook-f"></i></a></li>
                                            <li><a
                                                    href="https://www.instagram.com/rgmaruthuvamaiyam?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw%3D%3D"><i
                                                        class="fa-brands fa-instagram"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Carousel Section Start -->
    <div class="gallery-carousel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Scrolling Content Start -->
                    <div class="gallery-carousel-box">
                        @if ($galleries->isNotEmpty())
                            <!-- First scrolling row -->
                            <div class="gallery-scrolling-content">
                                @foreach ($galleries as $gallery)
                                    <div class="gallery-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset($gallery->image) }}"
                                                alt="Gallery Image {{ $gallery->id }}">
                                        </figure>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Second scrolling row (duplicate for infinite scroll effect) -->
                            <div class="gallery-scrolling-content">
                                @foreach ($galleries as $gallery)
                                    <div class="gallery-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset($gallery->image) }}"
                                                alt="Gallery Image {{ $gallery->id }}">
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Fallback to static images if no gallery images exist -->
                            <div class="gallery-scrolling-content">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="gallery-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset("front/images/gallery-$i.jpg") }}"
                                                alt="Gallery {{ $i }}" width="300px">
                                        </figure>
                                    </div>
                                @endfor
                            </div>
                            <div class="gallery-scrolling-content">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="gallery-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset("front/images/gallery-$i.jpg") }}"
                                                alt="Gallery {{ $i }}" width="300px">
                                        </figure>
                                    </div>
                                @endfor
                            </div>
                        @endif
                    </div>
                    <!-- Scrolling Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Gallery Carousel Section End -->

    <!-- Our Service Start -->
    <div class="our-service">
        <div class="container">
            <div class="row align-items-center section-row">
                <div class="col-lg-7">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">comprehensive services</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Integrated Healthcare</span> For All
                            Your Needs</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-5">
                    <!-- Section Button Start -->
                    <div class="section-btn wow fadeInUp" data-wow-delay="0.25s">
                        <a href="{{ route('services') }}" class="btn-default">view all services</a>
                    </div>
                    <!-- Section Button End -->
                </div>
            </div>

            <div class="row">
                @foreach ($services as $index => $service)
                    <div class="col-lg-3 col-md-6">
                        <!-- Service Item Start -->
                        <div class="service-item wow fadeInUp" data-wow-delay="{{ $index * 0.2 }}s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                @if ($service->image)
                                    @php
                                        $extension = pathinfo($service->image, PATHINFO_EXTENSION);
                                    @endphp
                                    @if (strtolower($extension) == 'svg')
                                        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}"
                                            style="width: 60px; height: 60px;">
                                    @else
                                        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}">
                                    @endif
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

                <div class="col-lg-6">
                    <!-- Service Item Start -->
                    <div class="service-item service-cta-item wow fadeInUp" data-wow-delay="1.2s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-cta.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Service Body Start -->
                        <div class="service-body">
                            <h3>need quality healthcare in Udumalpet?</h3>
                            <p>Don't worry! You no longer need to travel to distant cities for expert treatment. Get
                                comprehensive medical care right here at Rotary Galaxy Medical Center.</p>
                        </div>
                        <!-- Service Body End -->

                        <!-- Service Footer Start -->
                        <div class="service-cta-btn">
                            <a href="tel:7900880078" class="btn-default">Call Now</a>
                            <a href="{{ route('patient.login') }}" class="btn-default">Book Appointment</a>
                        </div>
                        <!-- Service Footer End -->
                    </div>
                    <!-- Service Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Service End -->

    <!-- Solution Your Plan Start -->
    <div class="solution-your-plan">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Solution Plan Image Start -->
                    <div class="solution-plan-image">
                        <div class="solution-plan-img-1">
                            <figure class="image-anime reveal">
                                <img src="{{ asset('front/images/solution-plan-img-1.jpg') }}" alt="">
                            </figure>
                        </div>

                        <div class="solution-plan-img-2">
                            <figure class="image-anime reveal">
                                <img src="{{ asset('front/images/solution-plan-img-2.jpg') }}" alt="">
                            </figure>
                        </div>
                    </div>
                    <!-- Solution Plan Image End -->
                </div>
                <div class="col-lg-6">
                    <!-- Solution Plan Content Start -->
                    <div class="solution-plan-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">special offers</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"> <span>Save Money</span> On Medicines &
                                Health Checkups</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">"Save money on buying medicines! Use the
                                service of Rotary Galaxy Udumalpet Galaxy!" We offer unique special offers to our regular
                                monthly medicine customers.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Solution Plan Body Start -->
                        <div class="solution-plan-body wow fadeInUp" data-wow-delay="0.5s">
                            <ul>
                                <li>Monthly medicine discounts for regular customers</li>
                                <li>Free home delivery service for medicines</li>
                                <li>Special rates on comprehensive health packages</li>
                            </ul>
                        </div>
                        <!-- Solution Plan Body End -->

                        <!-- Solution Plan Counter Start -->
                        <div class="solution-plan-counter">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <!-- Solution Counter Item Start -->
                                    <div class="solution-counter-item">
                                        <div class="icon-box">
                                            <img src="{{ asset('front/images/icon-solution-counter-1.svg') }}"
                                                alt="">
                                        </div>

                                        <div class="solution-counter-content">
                                            <h3><span class="counter">1500</span>+</h3>
                                            <p>regular patients</p>
                                        </div>
                                    </div>
                                    <!-- Solution Counter Item End -->
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <!-- Solution Counter Item Start -->
                                    <div class="solution-counter-item">
                                        <div class="icon-box">
                                            <img src="{{ asset('front/images/icon-solution-counter-2.svg') }}"
                                                alt="">
                                        </div>

                                        <div class="solution-counter-content">
                                            <h3><span class="counter">98</span>%</h3>
                                            <p>patient satisfaction</p>
                                        </div>
                                    </div>
                                    <!-- Solution Counter Item End -->
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <!-- Solution Counter Item Start -->
                                    <div class="solution-counter-item">
                                        <div class="icon-box">
                                            <img src="{{ asset('front/images/icon-solution-counter-3.svg') }}"
                                                alt="">
                                        </div>

                                        <div class="solution-counter-content">
                                            <h3><span class="counter">24</span>/7</h3>
                                            <p>service availability</p>
                                        </div>
                                    </div>
                                    <!-- Solution Counter Item End -->
                                </div>
                            </div>
                        </div>
                        <!-- Solution Plan Counter End -->
                    </div>
                </div>
                <!-- Solution Plan Content End -->
            </div>
        </div>
    </div>
    <!-- Solution Your Plan End -->

    <!-- Why Choose Us Start -->
    <div class="why-choose-us">
        <div class="container">
            <div class="row section-row">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">Rotary Galaxy Medical Centre</h3>
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
                            @php
                                $whyChoose1 = [
                                    ['icon' => 'icon-why-us-1.svg', 'title' => 'expert medical team', 'delay' => 0],
                                    [
                                        'icon' => 'icon-why-us-2.svg',
                                        'title' => 'comprehensive care',
                                        'delay' => 0.25,
                                    ],
                                    [
                                        'icon' => 'icon-why-us-3.svg',
                                        'title' => 'affordable rates',
                                        'delay' => 0.5,
                                    ],
                                ];
                            @endphp

                            @foreach ($whyChoose1 as $item)
                                <!-- Why Choose Item Start -->
                                <div class="why-choose-item wow fadeInUp" data-wow-delay="{{ $item['delay'] }}s">
                                    <!-- Icon Box Start -->
                                    <div class="icon-box">
                                        <img src="{{ asset('front/images/' . $item['icon']) }}" alt="">
                                    </div>
                                    <!-- Icon Box End -->

                                    <!-- Why Choose Content Start -->
                                    <div class="why-choose-content">
                                        <h3>{{ $item['title'] }}</h3>
                                        <p>Experienced doctors and specialists available locally for all your healthcare
                                            needs.</p>
                                    </div>
                                    <!-- Why Choose Content End -->
                                </div>
                                <!-- Why Choose Item End -->
                            @endforeach
                        </div>
                        <!-- Why Choose Box End -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Why Choose Box Start -->
                        <div class="why-choose-box-2">
                            @php
                                $whyChoose2 = [
                                    ['icon' => 'icon-why-us-4.svg', 'title' => 'advanced equipment', 'delay' => 0],
                                    [
                                        'icon' => 'icon-why-us-5.svg',
                                        'title' => '24/7 availability',
                                        'delay' => 0.25,
                                    ],
                                    ['icon' => 'icon-why-us-6.svg', 'title' => 'family care', 'delay' => 0.5],
                                ];
                            @endphp

                            @foreach ($whyChoose2 as $item)
                                <!-- Why Choose Item Start -->
                                <div class="why-choose-item wow fadeInUp" data-wow-delay="{{ $item['delay'] }}s">
                                    <!-- Icon Box Start -->
                                    <div class="icon-box">
                                        <img src="{{ asset('front/images/' . $item['icon']) }}" alt="">
                                    </div>
                                    <!-- Icon Box End -->

                                    <!-- Why Choose Content Start -->
                                    <div class="why-choose-content">
                                        <h3>{{ $item['title'] }}</h3>
                                        <p>Modern medical facilities with round-the-clock emergency services for your
                                            family.</p>
                                    </div>
                                    <!-- Why Choose Content End -->
                                </div>
                                <!-- Why Choose Item End -->
                            @endforeach
                        </div>
                        <!-- Why Choose Box End -->
                    </div>

                    <div class="col-lg-12">
                        <!-- Why Choose Image Start -->
                        <div class="why-choose-image">
                            <img src="{{ asset('front/images/why-us-img.png') }}" alt="">
                        </div>
                        <!-- Why Choose Image End -->
                    </div>
                </div>
            </div>
            <!-- Why Choose Us Box End -->
        </div>
    </div>
    <!-- Why Choose Us End -->

    <!-- Need Attention Start -->
    <div class="need-attention parallaxie">
        <div class="container">
            <div class="row section-row">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">health checkups</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Comprehensive Health Packages</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.25s">"The best gift you give your family is your good
                        health. It's smarter to prevent disease than to fight it later. One day of testing... years of
                        peace!" Get full body checkups today.</p>
                </div>
                <!-- Section Title End -->
            </div>

            <div class="row">
                @php
                    $attentionAreas = [
                        ['icon' => 'icon-need-attention-1.svg', 'text' => 'sugar test', 'delay' => 0],
                        ['icon' => 'icon-need-attention-2.svg', 'text' => 'cholesterol', 'delay' => 0],
                        ['icon' => 'icon-need-attention-3.svg', 'text' => 'blood tests', 'delay' => 0],
                        ['icon' => 'icon-need-attention-4.svg', 'text' => 'thyroid test', 'delay' => 0.25],
                        ['icon' => 'icon-need-attention-5.svg', 'text' => 'kidney function', 'delay' => 0.25],
                        ['icon' => 'icon-need-attention-6.svg', 'text' => 'heart ECG', 'delay' => 0.25],
                        ['icon' => 'icon-need-attention-7.svg', 'text' => 'liver function', 'delay' => 0.5],
                        ['icon' => 'icon-need-attention-8.svg', 'text' => 'vitamin tests', 'delay' => 0.5],
                        ['icon' => 'icon-need-attention-9.svg', 'text' => 'full body check', 'delay' => 0.5],
                    ];
                @endphp

                @foreach ($attentionAreas as $area)
                    <div class="col-lg-4 col-md-4 col-6">
                        <!-- Need Attention List Start -->
                        <div class="need-attention-list wow fadeInUp" data-wow-delay="{{ $area['delay'] }}s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="{{ asset('front/images/' . $area['icon']) }}" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Need Attention Content Start -->
                            <div class="need-attention-content">
                                <p>{{ $area['text'] }}</p>
                            </div>
                            <!-- Need Attention Content End -->
                        </div>
                        <!-- Need Attention List End -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Need Attention End -->

    <!-- Our Team Start
        <div class="our-team">
            <div class="container">
                <div class="row align-items-center section-row">
                    <div class="col-lg-9">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">medical specialists</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Our Experienced</span> Doctors &
                                Specialists Team</h2>
                        </div>
                    </div>

                </div>

                <div class="row">
                    @foreach ($teamMembers as $index => $member)
    <div class="col-lg-3 col-md-6">
                            <div class="team-member-item wow fadeInUp" data-wow-delay="{{ $index * 0.25 }}s">
                                <div class="team-image">
                                    <figure class="image-anime">
                                        @if ($member->image)
    @php
        $extension = pathinfo($member->image, PATHINFO_EXTENSION);
    @endphp
                                            @if (strtolower($extension) == 'svg')
    <img src="{{ asset($member->image) }}" alt="{{ $member->name }}"
                                                    style="width: 100%; height: auto; background: #f8f9fa; padding: 20px;">
@else
    <img src="{{ asset($member->image) }}" alt="{{ $member->name }}"
                                                    style="width: 100%; height: auto; object-fit: cover;">
    @endif
@else
    <img src="{{ asset('front/images/team-placeholder.jpg') }}"
                                                alt="{{ $member->name }}">
    @endif
                                    </figure>

                                    <div class="team-social-icon">
                                        <ul>
                                            <li>{{ $member->role }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h3>{{ $member->name }}</h3>
                                </div>
                            </div>
                        </div>
    @endforeach
                </div>
            </div>
        </div>
        Our Team End -->

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
                                @foreach ($reviews as $review)
                                    <!-- Testimonial Slide Start -->
                                    <div class="swiper-slide">
                                        <div class="testimonial-item">
                                            <div class="testimonial-header">
                                                <div class="testimonial-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->star_count)
                                                            <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                                                        @else
                                                            <i class="fa-regular fa-star" style="color: #ccc;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="testimonial-content">
                                                    <p>"{{ $review->review }}"</p>
                                                </div>
                                            </div>
                                            <div class="testimonial-body">
                                                <div class="author-content">
                                                    <h3>{{ $review->name }}</h3>
                                                    <p class="rating-text">
                                                        @if ($review->star_count == 5)
                                                            Excellent
                                                        @elseif($review->star_count == 4)
                                                            Very Good
                                                        @elseif($review->star_count == 3)
                                                            Good
                                                        @elseif($review->star_count == 2)
                                                            Average
                                                        @else
                                                            Poor
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Testimonial Slide End -->
                                @endforeach
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

    {{-- <!-- Our Blog Section End -->
    <div class="our-blog">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-9">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">health awareness</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Latest</span> Health Tips & Updates
                        </h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-3">
                    <!-- Section Button Start -->
                    <div class="section-btn wow fadeInUp" data-wow-delay="0.25s">
                        <a href="{{ route('blog') }}" class="btn-default">read all tips</a>
                    </div>
                    <!-- Section Button End -->
                </div>
            </div>

            <div class="row">
                @php
                    $blogs = [
                        [
                            'image' => 'post-1.jpg',
                            'title' => 'Diabetes foot care - essential tips for patients',
                            'delay' => 0,
                        ],
                        [
                            'image' => 'post-2.jpg',
                            'title' => 'Monthly medicine savings - how to get discounts',
                            'delay' => 0.2,
                        ],
                        [
                            'image' => 'post-3.jpg',
                            'title' => 'Thyroid symptoms - when to get tested',
                            'delay' => 0.4,
                        ],
                    ];
                @endphp

                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <!-- Blog Item Start -->
                        <div class="blog-item wow fadeInUp" data-wow-delay="{{ $blog['delay'] }}s">
                            <!-- Post Featured Image Start-->
                            <div class="post-featured-image" data-cursor-text="View">
                                <figure>
                                    <a href="#" class="image-anime">
                                        <img src="{{ asset('front/images/' . $blog['image']) }}"
                                            alt="{{ $blog['title'] }}">
                                    </a>
                                </figure>
                            </div>
                            <!-- Post Featured Image End -->

                            <!-- post Item Content Start -->
                            <div class="post-item-content">
                                <!-- post Item Body Start -->
                                <div class="post-item-body">
                                    <h2><a href="#">{{ $blog['title'] }}</a></h2>
                                </div>
                                <!-- Post Item Body End-->

                                <!-- Post Item Footer Start-->
                                <div class="post-item-footer">
                                    <a href="#" class="readmore-btn">read more</a>
                                </div>
                                <!-- Post Item Footer End-->
                            </div>
                            <!-- post Item Content End -->
                        </div>
                        <!-- Blog Item End -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Our Blog End --> --}}
@endsection
