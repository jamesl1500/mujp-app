<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title') @yield('title') | @endif Museum of Jewish Philanthropists</title>

    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('frontend/images/favicon/museum-of-jewish-philanthropists-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('frontend/images/favicon/museum-of-jewish-philanthropists-icon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('frontend/images/favicon/museum-of-jewish-philanthropists-icon-16x16.png')}}">
    <link rel="manifest" href="{{asset('frontend/images/favicon/site.webmanifest')}}">

    <!-- Fonts URL -->
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700%7CPlayfair+Display:400,500,600,700,800,900%7CWork+Sans:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/jquery.mCustomScrollbar.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/hover-min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/muzex-icons.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/css/custom.css')}}">

    @yield('vendor-style')
    @yield('page-style')
</head>

<body>
<!-- Preloader -->
<div class="preloader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>
<!-- Preloader -->

<div class="page-wrapper">
    @include('frontend.panels.header')

    @yield('content')

    @include('frontend.panels.footer')
</div>

<div class="search-popup">
    <div class="search-popup__overlay custom-cursor__overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
    <div class="search-popup__inner">
        <form method="get" action="{{route('frontend.philanthropists.index')}}" class="search-popup__form">
            <input type="text" name="search" placeholder="Type here to Search....">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
</div>

<div class="side-content__block">
    <div class="side-content__block-overlay custom-cursor__overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
    <div class="side-content__block-inner ">
        <a href="{{route('frontend.home')}}">
            <img src="{{asset('frontend/images/museum-of-jewish-philanthropists-logo.png')}}" alt="" width="200">
        </a>
        <div class="side-content__block-about">
            <h3 class="side-content__block__title">About Us</h3>
            <p class="side-content__block-about__text">The goal of MUJP.com is to develop, maintain, and offer an extensive user friendly online archive to help people study Jewish History and Genealogy, and connect to their roots.</p>
            <a href="{{route('frontend.about')}}" class="thm-btn side-content__block-about__btn">More About Us</a>
        </div>
        <hr class="side-content__block-line"/>
        <div class="side-content__block-contact">
            <h3 class="side-content__block__title">Contact Us</h3>
            <ul class="side-content__block-contact__list">
                <li class="side-content__block-contact__list-item">
                    <i class="fa fa-map-marker"></i>
                    Museum of Jewish Philanthropists
                </li>
                <li class="side-content__block-contact__list-item">
                    <i class="fa fa-phone"></i>
                    <p><a href="tel:(555)-555-5500">(555) 555-5500</a></p>
                </li>
                <li class="side-content__block-contact__list-item">
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:info@mujp.com">info@mujp.com</a>
                </li>
                <!--
                <li class="side-content__block-contact__list-item">
                    <i class="fa fa-clock"></i>
                    Week Days: 09.00 to 18.00 Sunday: Closed
                </li>
                -->
            </ul>
        </div>
        <p class="side-content__block__text site-footer__copy-text"><a href="#">MUJP</a> <i class="fa fa-copyright"></i>
            2021 All Right Reserved</p>
    </div>
</div>

<div class="side-menu__block">
    <a href="#" class="side-menu__toggler side-menu__close-btn">
        <i class="fa fa-times"></i>
    </a>

    <div class="side-menu__block-overlay custom-cursor__overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
    <div class="side-menu__block-inner ">
        <a href="/" class="side-menu__logo">
            <img src="{{asset('frontend/images/museum-of-jewish-philanthropists-logo-light.png')}}" alt="" width="240">
        </a>

        <nav class="mobile-nav__container" id="mobile-menu">
            <!-- content is loading via js -->

        </nav>




        <div class="login">
            @guest
                <a class="login-link" href="{{ route('login') }}" target="_blank">Login</a>
                <a class="login-link" href="{{ route('register') }}" target="_blank">Sign Up</a>
            @else
                <a class="login-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>

        <p class="side-menu__block__copy">(c) 2021 <a href="#">MUJP</a> - All rights reserved.</p>
        <div class="side-menu__social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-google-plus"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-pinterest-p"></i></a>
        </div>
    </div>
</div>

<a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>

<!-- Template JS -->

<script src="{{asset('frontend/js/jquery.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('frontend/js/isotope.js')}}"></script>
<script src="{{asset('frontend/js/jquery.ajaxchimp.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('frontend/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('frontend/js/TweenMax.min.js')}}"></script>
<script src="{{asset('frontend/js/waypoints.min.js')}}"></script>
<script src="{{asset('frontend/js/wow.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.lettering.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.circleType.js')}}"></script>

<!-- Custom Scripts -->
<script src="{{asset('frontend/js/theme.js')}}"></script>
<script src="{{asset('frontend/js/donate-currency.js')}}"></script>

<script>
    $('#mobile-menu .main-nav__navigation-box').append(`<li><a target="_blank" href="@can('isAdmin') {{route('philanthropists.add')}} @endcan @can('isMember') {{route('member.submit')}} @endcan" @guest onclick="event.preventDefault();$('.topbar-modal').modal();" @endguest>Submit a Philanthropist</a></li>`);
</script>

@yield('vendor-script')
@yield('page-script')

</body>

</html>