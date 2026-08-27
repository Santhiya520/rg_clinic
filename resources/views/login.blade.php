<!DOCTYPE html>
<html lang="zxx" class="js">
<!-- Mirrored from dashlite.net/demo9/pages/auths/auth-login-v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Nov 2025 10:11:49 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <link rel="icon" href="{{ asset('images/favicon.jpeg') }}" type="image/jpeg">
    <title>RG Maruthuvamaiyam</title>
    <link rel="stylesheet" href="{{ asset('css/dashlite9b70.css?ver=3.3.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('css/theme9b70.css?ver=3.3.0') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-91615293-4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-91615293-4');
    </script>
    <style>
        .login-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .login-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .login-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>

<body class="nk-body ui-rounder npc-general ui-light pg-auth">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap nk-wrap-nosidebar">
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">

                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="brand-logo pb-4 text-center"><a href="../../index.html"
                                        class="logo-link"><img class="logo-light logo-img logo-img-lg"
                                            src="{{ asset('images/logo.png') }}"
                                            srcset="{{ asset('images/logo.png 2x') }}" alt="logo"><img
                                            class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                            srcset="{{ asset('images/logo.png 2x') }}" alt="logo-dark"></a></div>
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                {{-- LOGIN FORM --}}
                                <h6 class="text-center" style="color: #1e55a7;font-weight:1000;font-size: 20px;">Staff
                                    Login</h6>
                                <form action="{{ route('login.validate') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group"><label class="form-label" for="default-01">Email
                                                or Username</label></div>
                                        <div class="form-control-wrap"><input type="text" name="email"
                                                class="form-control form-control-lg" id="default-01"
                                                placeholder="Enter your email address or username"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group"><label class="form-label"
                                                for="password">Passcode</label></div>
                                        <div class="form-control-wrap"><a href="#"
                                                class="form-icon form-icon-right passcode-switch lg"
                                                data-target="password"><em
                                                    class="passcode-icon icon-show icon ni ni-eye"></em><em
                                                    class="passcode-icon icon-hide icon ni ni-eye-off"></em></a><input
                                                type="password" name="password" class="form-control form-control-lg"
                                                id="password" placeholder="Enter your passcode"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Sign In" name="submit"
                                            class="btn btn-lg btn-primary btn-block">
                                    </div>
                                </form>
                                <div class="login-links">
                                     <span class="mx-2">•</span>
                                    <a href="{{ route('patient.login') }}">{{ __('Patient Login') }}</a>
                                     <span class="mx-2">•</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bundle9b70.js?ver=3.3.0') }}"></script>
    <script src="{{ asset('js/scripts9b70.js?ver=3.3.0') }}"></script>
    <script src="{{ asset('js/demo-settings9b70.js?ver=3.3.0') }}"></script>

</html>
