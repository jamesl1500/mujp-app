@extends('layouts.frontend.master')

@section('title')
    About MUJP
@endsection

@section('content')
    <section class="page-header" style="background-image: url({{asset('frontend/images/banner/search-page-banner.jpg')}});">
        <div class="container">
            <h2>About MUJP</h2>
        </div>
    </section>

    <section class="about-four">
        <div class="container">
            <div class="about-four__image wow fadeInRight" data-wow-duration="1500ms">
                <img src="{{asset('frontend/images/resources/about-page-image-1.jpg')}}" alt="">
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-four__content">
                        <div class="block-title">
                            <p>About MUJP</p>
                            <h3>Museum of Jewish Philanthropists</h3>
                        </div>
                        <div class="about-four__highlite-text">
                            <p> Welcome to the Museum of Jewish Philanthropists. MUJP is one of the largest professionally compiled Jewish Philanthropist resources of its kind currently available. </p>
                        </div>
                        <p>MUJP.org was founded by Rabbi Avraham Laber in 2004. A number of photographers who skillfully capture images, and data entry workers develop the collection. The goal of MUJP.org is to develop, maintain, and offer an extensive user friendly online archive to help people study Jewish History and Genealogy, and connect to their roots.</p>
                        <p>The collection includes hundreds of complete Jewish cemeteries around the world, with a listing and linked image of every monument. Many immigration documents called declaration of Intention, and a number of information rich books are included in the database. The database continues to grow. </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('frontend.panels.cta')
@endsection