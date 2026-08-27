@extends('front.layouts.app')

@section('title', 'Contact Rotary Galaxy Medical Center - Udumalpet')
@section('description',
    'Contact Rotary Galaxy Medical Center in Udumalpet for OPD/IPD, lab tests, pharmacy,
    physiotherapy, radiology and emergency care services')
@section('keywords',
    'contact medical center Udumalpet, hospital contact, emergency numbers, appointment booking,
    healthcare contact')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Contact Us</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">contact us</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Contact Start -->
    <div class="page-contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-green-location.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Contact Info Content Start -->
                        <div class="contact-info-content">
                            <h3>location</h3>
                            <p>Sankar Nagar Bus Stop, Dharapuram Road, Udumalpet - 642126</p>
                        </div>
                        <!-- Contact Info Content End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-green-mail.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Contact Info Content Start -->
                        <div class="contact-info-content">
                            <h3>email</h3>
                            <p>rgmaruthuvamaiyam@gmail.com</p>
                        </div>
                        <!-- Contact Info Content End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-green-phone.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Contact Info Content Start -->
                        <div class="contact-info-content">
                            <h3>emergency numbers</h3>
                            <p>79 00 88 00 78</p>
                            <p>79 00 65 00 78</p>
                        </div>
                        <!-- Contact Info Content End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.75s">
                        <!-- Icon Box Start -->
                        <div class="icon-box">
                            <img src="{{ asset('front/images/icon-green-hour.svg') }}" alt="">
                        </div>
                        <!-- Icon Box End -->

                        <!-- Contact Info Content Start -->
                        <div class="contact-info-content">
                            <h3>working hours</h3>
                            <p>OPD: 8:00 AM to 9:00 PM</p>
                            <p>Emergency: 24/7</p>
                        </div>
                        <!-- Contact Info Content End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Contact End -->

    <!-- Contact Form Start -->
    <div class="contact-us-form">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Contact Us Image Start -->
                    <div class="contact-us-img">
                        <figure class="reveal image-anime">
                            <img src="{{ asset('front/images/contact-us-img.jpg') }}"
                                alt="Rotary Galaxy Medical Center Contact">
                        </figure>
                    </div>
                    <!-- Contact Us Image End -->
                </div>
                <div class="col-lg-6">
                    <div class="contact-form">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">contact us</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Get</span> In Touch With Us</h2>
                        </div>
                        <!-- Section Title End -->

                        <form id="contactForm" method="POST" class="wow fadeInUp" data-wow-delay="0.25s">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="name" class="form-control" id="fullname"
                                        placeholder="Enter Name" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Enter Email" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="phone" class="form-control" id="phone"
                                        placeholder="Phone Number" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="subject" class="form-control" id="subject"
                                        placeholder="Subject" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-12 mb-5">
                                    <textarea name="message" class="form-control" id="message" rows="5" placeholder="Your Message" required=""></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn-default" id="submitBtn">send message</button>
                                    <div id="formMessage" class="mt-3"></div>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Form End -->

    <!-- Google Map Start -->
    <div class="google-map">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Google Map Iframe Start -->
                    <div class="google-map-iframe">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3917.732369684079!2d77.23452197580412!3d10.90821788924324!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba9c0d1e2c4c8cf%3A0xd2e82738f90b78ec!2sSankar%20Nagar%20Bus%20Stop%2C%20Udumalpet%2C%20Tamil%20Nadu%20642126!5e0!3m2!1sen!2sin!4v1703158537552!5m2!1sen!2sin"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <!-- Google Map Iframe End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Google Map End -->

@endsection
@push('scripts')
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const formMessage = document.getElementById('formMessage');

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

            // Clear previous messages
            formMessage.innerHTML = '';
            formMessage.className = 'mt-3';

            // Get form data
            const formData = new FormData(form);

            // Send AJAX request
            fetch('{{ route('enquiry.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        formMessage.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        form.reset(); // Reset form
                    } else {
                        // Show error message
                        formMessage.innerHTML = '<div class="alert alert-danger">' + (data.message ||
                            'Something went wrong.') + '</div>';
                    }
                })
                .catch(error => {
                    formMessage.innerHTML =
                        '<div class="alert alert-danger">Network error. Please try again.</div>';
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'send message';

                    // Scroll to message
                    formMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                });
        });
    </script>
@endpush
