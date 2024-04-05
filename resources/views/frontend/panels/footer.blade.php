<footer class="site-footer">
    <div class="site-footer__upper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="footer-widget footer-widget__about">
                        <h3 class="footer-widget__title">About MUJP</h3>
                        <p>The goal of MUJP.org is to develop, maintain, and offer an extensive user friendly online archive to help people study Jewish Philanthropists, and connect to their roots.</p>

                        <!--
                        <div class="newsletter-form">
                            <h3 class="footer-widget__title">Newsletter</h3>
                            <form action="#" class="search-popup__form">
                                <input type="text" name="search" placeholder="Join Our Free Newsletter..." s>
                                <button type="submit"><i class="fa fa-plus"></i></button>
                            </form>
                        </div>
                        -->
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-widget footer-widget__links">
                        <h3 class="footer-widget__title">Quick Link</h3>
                        <ul class="footer-widget__links-list list-unstyled">
                            <li>
                                <a href="{{route('frontend.home')}}">Home</a>
                            </li>
                            <li>
                                <a href="{{route('frontend.philanthropists.index', ['view' => 'column'])}}">Philanthropists</a>
                            </li>
                            <li>
                                <a href="{{route('frontend.about')}}">About MUJP</a>
                            </li>
                            <li>
                                <a href="{{route('frontend.donate')}}">Donate</a>
                            </li>
                            <li>
                                <a href="{{route('frontend.contact')}}">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-widget footer-widget__contact">
                        <h3 class="footer-widget__title">Contact</h3>
                        <p>Museum of Jewish Philanthropists</p>
                        <p><a href="tel:(555)-555-5500">(555) 555-5500</a></p>
                        <p><a href="mailto:info@mujp.com">info@mujp.org</a></p>
                    </div>
                </div>
                <!--
                <div class="col-lg-3">
                    <div class="footer-widget footer-widget__open-hrs">
                        <h3 class="footer-widget__title">Open Hours</h3>
                        <p>Daily: 9.30 AM–6.00 PM <br>Sunday & Holidays: Closed</p>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container">
            <div class="inner-container">
                <p>&copy; Copyright 2021 MUJP.org All Rights Reserved.</p>
                <a href="{{route('frontend.home')}}" class="site-footer__bottom-logo">
                    <img src="{{asset('frontend/images/museum-of-jewish-philanthropists-footer-logo.png')}}" width="230" alt="">
                </a>
                <div class="site-footer__bottom-links">
                    <a href="#">Terms & Conditions</a>&nbsp;-&nbsp;<a href="#">Privacy Policy & Terms of Use</a>
                </div>
            </div>
        </div>
    </div>
</footer>