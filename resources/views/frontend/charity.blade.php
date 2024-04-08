@extends('layouts.frontend.master')

@section('title')
    Charity
@endsection

@section('content')
<section class="page-header" style="background-image: url({{asset('frontend/images/banner/search-page-banner.jpg')}});">
    <div class="container">
        <h2>Charity</h2>
        <h4>Learn more about our charities</h4>
    </div>
</section>

<section class="about-four">
    <div class="container">
        <div class="about-four__image wow fadeInRight" data-wow-duration="1500ms">
            <img src="{{asset('frontend/images/resources/about-page-image-1.jpg')}}" alt="">
        </div>
        <!-- Stories -->
        <div class="row">
            <div class="col-lg-6">
                <div class="about-four__content">
                    <div class="block-title">
                        <p>Learn more about our charities</p>
                        <h3>Jewish Teachings</h3>
                    </div>
                    <div class="about-four__highlite-text">
                        <p> Welcome to the Museum of Jewish Philanthropists. MUJP is one of the largest professionally compiled Jewish Philanthropist resources of its kind currently available. </p>
                        <p>MUJP.org was founded by Rabbi Avraham Laber in 2004. A number of photographers who skillfully capture images, and data entry workers develop the collection. The goal of MUJP.org is to develop, maintain, and offer an extensive user friendly online archive to help people study Jewish History and Genealogy, and connect to their roots.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.panels.cta')
@endsection