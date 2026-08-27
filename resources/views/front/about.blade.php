@extends('front.layouts.app')

@section('title', 'About Rotary Galaxy Medical Center - Complete Healthcare in Udumalpet')
@section('description',
    'Learn about Rotary Galaxy Medical Center - Your trusted healthcare partner providing OPD/IPD,
    lab, pharmacy, physiotherapy, and radiology services in Udumalpet')
@section('keywords',
    'medical center Udumalpet, hospital about, healthcare services, OPD IPD, diagnostic lab, pharmacy,
    physiotherapy, radiology')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">About Us</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">about us</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

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
    <!-- Company Counter Start -->
    <div class="company-counter">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Company Counter Item Start -->
                    <div class="company-counter-item">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-counter-1.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Company Counter Content Start -->
                        <div class="company-counter-content">
                            <h3><span class="counter">1500</span>+</h3>
                            <p>regular patients</p>
                        </div>
                        <!-- Company Counter Content End -->
                    </div>
                    <!-- Company Counter Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Company Counter Item Start -->
                    <div class="company-counter-item">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-counter-2.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Company Counter Content Start -->
                        <div class="company-counter-content">
                            <h3><span class="counter">98</span>%</h3>
                            <p>patient satisfaction</p>
                        </div>
                        <!-- Company Counter Content End -->
                    </div>
                    <!-- Company Counter Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Company Counter Item Start -->
                    <div class="company-counter-item">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-counter-3.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Company Counter Content Start -->
                        <div class="company-counter-content">
                            <h3><span class="counter">24</span>/7</h3>
                            <p>service availability</p>
                        </div>
                        <!-- Company Counter Content End -->
                    </div>
                    <!-- Company Counter Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Company Counter Item Start -->
                    <div class="company-counter-item">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-counter-4.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Company Counter Content Start -->
                        <div class="company-counter-content">
                            <h3><span class="counter">1</span>+</h3>
                            <p>year of service</p>
                        </div>
                        <!-- Company Counter Content End -->
                    </div>
                    <!-- Company Counter Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Company Counter End -->

    <!-- Mission Vision Start -->
    <div class="mission-vision">
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
                                hospital operates under the able leadership of its Our Mentor, Rtn.Adv.V. Ponrraaj.
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
    <!-- Quality Treatment Section Start -->
    <div class="quality-treatment">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Video Treatment Video Start -->
                    <div class="quality-treatment-video" data-cursor-text="Play">
                        <!-- Video Image Start -->
                        <div class="video-image">
                            <a href="https://www.youtube.com/watch?v=Y-x0efG1seA" class="popup-video">
                                <figure class="image-anime">
                                    <img src="{{ asset('front/images/quality-treatment-video-img.jpg') }}"
                                        alt="Rotary Galaxy Medical Center Tour">
                                </figure>
                            </a>
                        </div>
                        <!-- Video Image End -->

                        <!-- Video Play Button Start -->
                        <div class="video-play-button">
                            <a href="https://www.youtube.com/watch?v=Y-x0efG1seA" class="popup-video">
                                <i class="fa-solid fa-play"></i>
                            </a>
                        </div>
                        <!-- Video Play Button End -->
                    </div>
                    <!-- Video Treatment Video End -->
                </div>

                <div class="col-lg-6">
                    <!-- Quality Treatment Content Start -->
                    <div class="quality-treatment-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">special offers</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Save Money</span> On Medicines &
                                Health Checkups</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">"Save money on buying medicines! Use the
                                service of Rotary Galaxy Udumalpet Galaxy!" We offer unique special offers to our regular
                                monthly medicine customers with free home delivery service.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Quality Treatment Body Start -->
                        <div class="quality-treatment-body wow fadeInUp" data-wow-delay="0.5s">
                            <ul>
                                <li>Monthly medicine discounts for regular customers</li>
                                <li>Special rates on comprehensive health packages</li>
                                <li>Free home delivery service for medicines</li>
                                <li>Discounts on lab tests and diagnostics</li>
                            </ul>
                        </div>
                        <!-- Quality Treatment Body End -->
                    </div>
                    <!-- Quality Treatment Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Quality Treatment Section End -->

    <!-- Therapy Process Start -->
    <div class="therapy-process">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">our services process</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>How</span> We Serve You Better</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Therapy Process Item Start -->
                    <div class="therapy-process-item wow fadeInUp">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <figure class="image-anime">
                                <img src="{{ asset('front/images/rg-abt1.png') }}" alt="Medical Consultation">
                            </figure>
                        </div>
                        <!-- Icon Box End -->

                        <!-- Therapy Process Content Start -->
                        <div class="therapy-process-content">
                            <h3>expert consultation</h3>
                            <p>Get consultation from experienced doctors and specialists for accurate diagnosis and
                                treatment planning.</p>
                        </div>
                        <!-- Therapy Process Content End -->
                    </div>
                    <!-- Therapy Process Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Therapy Process Item Start -->
                    <div class="therapy-process-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <figure class="image-anime">
                                <img src="{{ asset('front/images/rg-abt2.jpeg') }}" alt="Diagnostic Tests">
                            </figure>
                        </div>
                        <!-- Icon Box End -->

                        <!-- Therapy Process Content Start -->
                        <div class="therapy-process-content">
                            <h3>advanced diagnostics</h3>
                            <p>Complete diagnostic tests including lab, ECG, radiology and imaging services with accurate
                                results.</p>
                        </div>
                        <!-- Therapy Process Content End -->
                    </div>
                    <!-- Therapy Process Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Therapy Process Item Start -->
                    <div class="therapy-process-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <figure class="image-anime">
                                <img src="{{ asset('front/images/rg-abt3.jpeg') }}" alt="Medical Treatment">
                            </figure>
                        </div>
                        <!-- Icon Box End -->

                        <!-- Therapy Process Content Start -->
                        <div class="therapy-process-content">
                            <h3>comprehensive treatment</h3>
                            <p>Complete medical treatment including OPD/IPD, physiotherapy, and specialist care for
                                recovery.</p>
                        </div>
                        <!-- Therapy Process Content End -->
                    </div>
                    <!-- Therapy Process Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Therapy Process Item Start -->
                    <div class="therapy-process-item wow fadeInUp" data-wow-delay="0.75s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <figure class="image-anime">
                                <img src="{{ asset('front/images/rg-abt4.jpeg') }}" alt="Follow Up Care">
                            </figure>
                        </div>
                        <!-- Icon Box End -->

                        <!-- Therapy Process Content Start -->
                        <div class="therapy-process-content">
                            <h3>follow-up & support</h3>
                            <p>Regular follow-up, medicine delivery, and continuous support for complete recovery and
                                wellness.</p>
                        </div>
                        <!-- Therapy Process Content End -->
                    </div>
                    <!-- Therapy Process Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Therapy Process End -->

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
@endsection
