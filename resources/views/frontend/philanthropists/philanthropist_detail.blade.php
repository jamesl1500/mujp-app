@extends('layouts.frontend.master')

@section('title')
    Detail
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('frontend/css/swiper-bundle.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('frontend/css/filecard.css')}}">

    <style>
        .swiper-container {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
            min-height: 420px;
        }

        .swiper-slide img {
            display: block;
            object-fit: cover;
            width: 56.25%;
            height: 100%;
        }

        .swiper-button-prev:after, .swiper-button-next:after {
            font-size: 1.5rem !important;
            font-family: "Font Awesome 5 Free";
            font-weight: 700;
        }

        .swiper-button-prev:after {
            content: "\f104";
        }

        .swiper-button-next:after {
            content: "\f105";
        }

        .swiper-button-prev, .swiper-button-next {
            color: white;
            background: #212529;
            border-radius: 50%;
            width: 3.5rem;
            height: 3.5rem;
        }

        .swiper-button-prev:hover, .swiper-button-next:hover {
            color: var(--thm-base);
        }

        .swiper-pagination-bullet-active {
            background-color: var(--thm-base) !important;
        }
    </style>
@endsection

@section('page-style')
    <style>
        .media-gallery__wrapper {
            position: relative;
            overflow: hidden;
            top: 1rem
        }

        .philanthropist-name {
            font-style: italic;
            font-size: 1.5rem;
            font-weight: 600;
            border-bottom: 1px solid #848484;
            border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: rgb(162, 169, 177);
        }
    </style>
@endsection

@section('content')

    <section class="page-header"
             style="background-image: url({{asset('frontend/images/banner/search-page-banner.jpg')}});">
        <div class="container">
            <h2>{{$philanthropist->firstname . ' '. $philanthropist->lastname}}</h2>
            <h3>{{$philanthropist->getBirthDeathStr()}}</h3>
        </div>
    </section>

    <section class="event-details">
        <div class="container">
            <div class="row high-gutter">
                <div class="col-lg-8">
                    <div class="event-details__main">
                        <!--
                        <div class="event-details__image">
                            <img src="assets/images/avatar/mujp-philanthropist-portrait.jpg" alt="">
                        </div>
                        -->
                        <div class="event-details__content">
                            <img src="{{$philanthropist->profileImage->first() ? str_replace('public', 'storage', asset($philanthropist->profileImage->first()->path)): asset('frontend/images/avatar/mujp-no-avatar.svg')}}"
                                 alt="">
                            <section id="info">
                                <h1 class="philanthropist-name">{{$philanthropist->firstname . ' '. $philanthropist->lastname}}</h1>
                                <h7>{{$philanthropist->getBirthDeathStr()}}</h7>
                            </section>
                            <section id="biography">
                                {!! $philanthropist->biography ? $philanthropist->biography : 'The biography of the philanthropist has not been entered yet.'!!}
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="event-details__booking">
                        <ul class="event-details__booking-info list-unstyled">
                            <!-- Gender -->
                            @if($philanthropist->gender)
                                <li>
                                    <span>Gender:</span>
                                    <i class="fas {{$philanthropist->gender == 'male' ? 'fa-mars' : 'fa-venus'}}"></i> {{ucfirst($philanthropist->gender)}}
                                </li>
                            @endif

                        <!-- City of Birth -->
                            @if(isset($philanthropist->city_of_birth) || isset($philanthropist->city_of_birth_other))
                                <li>
                                    <span>Country of Birth:</span>
                                    <i class="fas fa-map-marker-alt"></i> {{$philanthropist->city_of_birth ?
                                    ($philanthropist->city->state->country->name. ' / '. str_replace('Province', '', $philanthropist->city->state->name) . ' / ' .  $philanthropist->city->name) :
                                    $philanthropist->city_of_birth_other}}
                                </li>
                            @endif

                            @if(isset($philanthropist->country_of_most_lived_in) || isset($philanthropist->state_of_most_lived_in) || isset($philanthropist->city_of_most_lived_in) )
                                <li>
                                    <span>Country of Most Lived in:</span>
                                    <i class="fas fa-map-marker-alt"></i> {{$philanthropist->country_of_most_lived_in ? $philanthropist->countryOfMostLivedIn->name : 'Unknown'}}
                                    /
                                    {{$philanthropist->state_of_most_lived_in ? $philanthropist->stateOfMostLivedIn->name : 'Unknown'}}
                                    /
                                    {{$philanthropist->city_of_most_lived_in ? $philanthropist->cityOfMostLivedIn->name : 'Unknown'}}
                                </li>
                            @endif
                            @if(isset($philanthropist->business))
                                @if($philanthropist->business->industry || $philanthropist->business->industry_other)
                                    <li>

                                        <span>Industry:</span>
                                        <i class="fas fa-briefcase"></i> {{isset($philanthropist->business->industry) ?  $philanthropist->business->industry->name : $philanthropist->business->industry_other }}
                                    </li>
                                @endif
                            @endif
                            @if($philanthropist->familyMembers->count()> 0)
                                <li class="family-tree">
                                    <span>Family Tree:</span>
                                    <ul class="list-unstyled sidebar__cat-list">
                                        @foreach($philanthropist->familyMembers as $familyMember)
                                            <li>
                                                @if($familyMember->related_philanthropist_id)
                                                    <a href="{{route('frontend.philanthropist.show', $familyMember->related_philanthropist_id)}}"
                                                       target="_blank"
                                                       class="family-tree-item">{{ucfirst($familyMember->relationType->name)}}
                                                        : {{$familyMember->relatedPhilanthropist->firstname.' '.$familyMember->relatedPhilanthropist->lastname}}</a>
                                                @else
                                                    <a class="family-tree-item">{{ucfirst($familyMember->relationType->name)}}
                                                        : {{$familyMember->firstname.' '.$familyMember->lastname}}</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endisset
                        </ul>
                        <a href="#" class="thm-btn event-details__book-btn">Donate</a>
                        @can('isAdmin')
                            <a href="{{route('philanthropists.edit', $philanthropist->id)}}" target="_blank"
                               class="thm-btn event-details__book-btn mt-1"
                               style="background-color: rgba(255, 255, 255, .5);">Edit</a>
                        @endcan
                    </div>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <br/>
            <hr/>
        </div> <!-- end of container -->
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <section class="business-industry">
                        @if(isset($philanthropist->business))
                            <h4>Business & Industry</h4>
                            <table class="mt-1">
                                <tbody>
                                <tr>
                                    <th scope="row">Business Name:</th>
                                    <td>
                                        @if(isset($philanthropist->business))
                                            {{$philanthropist->business->name }}
                                        @else
                                            Unknown
                                    @endif
                                </tr>
                                <tr>
                                    <th scope="row">Industry:</th>
                                    <td>
                                        @if(isset($philanthropist->business))
                                            @if($philanthropist->business->industry || $philanthropist->business->industry_other)
                                                {{isset($philanthropist->business->industry) ?  $philanthropist->business->industry->name : $philanthropist->business->industry_other }}
                                            @else
                                                Unknown
                                            @endif
                                        @else
                                            Unknown
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        @endif
                    </section>
                </div> <!-- end of col -->
                <div class="col-12 col-md-6">
                    <section class="institutions">
                        @if($philanthropist->institutions->count())
                            <h4>Institutions</h4>
                            <ul>
                                @foreach($philanthropist->institutions as $philanthropistInstitution)
                                    <li>
                                        @if($philanthropistInstitution->role)
                                            {{$philanthropistInstitution->role->name}} of
                                        @endif
                                        {{$philanthropistInstitution->institution_id ? $philanthropistInstitution->institution->name :$philanthropistInstitution->institution_other}}
                                        @if($philanthropistInstitution->type)
                                            ({{ucfirst(trim($philanthropistInstitution->type->name))}}){{$philanthropistInstitution->city ? ',' : ''}}
                                        @endif
                                        {{$philanthropistInstitution->city ? ucfirst(trim($philanthropistInstitution->city->name)) . ' city' : ''}}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                </div>
            </div>
            @if($philanthropist->business || $philanthropist->institutions->count() )
                <br/>
                <hr/>
            @endif


            <div class="row">
                <div class="col-12 col-md-6">
                    @if($philanthropist->associatedPeoples->count())
                        <section class="associated-peoples">
                            <h4>Business People Closely Associated With</h4>
                            <ul>
                                @foreach($philanthropist->associatedPeoples as $associatedPeople)
                                    @if($associatedPeople->associated_philanthropist_id)
                                        <li>
                                            <a href="{{route('frontend.philanthropist.show', $associatedPeople->associated_philanthropist_id)}}"
                                               class="navigable-link"
                                               target="_blank">{{$associatedPeople->associatedPhilanthropist->firstname. ' ' .$associatedPeople->associatedPhilanthropist->lastname}}</a>
                                        </li>
                                    @else
                                        <li>{{$associatedPeople->firstname . ' ' . $associatedPeople->lastname}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            <br/>
                        </section>
                    @endif
                </div>
                <div class="col-12 col-md-6">
                    @if($philanthropist->familyMembers->count())
                        <section class="associated-peoples">
                            <h4>Family Tree</h4>
                            <ul>
                                @foreach($philanthropist->familyMembers as $familyMember)
                                    <li>
                                        @if($familyMember->related_philanthropist_id)
                                            <a href="{{route('frontend.philanthropist.show', $familyMember->related_philanthropist_id)}}"
                                               target="_blank"
                                               class="navigable-link">{{ucfirst(trim($familyMember->relationType->name))}}
                                                : {{$familyMember->relatedPhilanthropist->firstname.' '.$familyMember->relatedPhilanthropist->lastname}}</a>
                                        @else
                                            <a>{{ucfirst(trim($familyMember->relationType->name))}}
                                                : {{$familyMember->firstname.' '.$familyMember->lastname}}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            <br/>
                        </section>
                    @endif
                </div>
            </div>
            @if($philanthropist->associatedPeoples->count() || $philanthropist->familyMembers->count())
                <hr/>
        @endif


        <!-- Media Gallery -->
            <section id="media-gallery">
                @if($philanthropist->galleryImages && $philanthropist->galleryImages->count())
                    <h4>Media Gallery</h4>
                    <div class="media-gallery__wrapper">
                        <div class="swipper-container swiper-gallery">
                            <div class="swiper-wrapper">
                                @foreach($philanthropist->galleryImages as $galleryImage)
                                    <div class="swiper-slide"><img
                                                src="{{str_replace('public', 'storage', asset($galleryImage->path))}}"
                                                alt=""></div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next "></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <br/>
                    <hr/>
                @endif
            </section>

            <!-- Articles -->
            <section id="articles">
                @if($philanthropist->articleFiles->count())
                    <h4>Articles</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <div class="row">
                                    @foreach($philanthropist->articleFiles as $articleFile)
                                        <div class="col-lg-3 col-xl-2">
                                            <div class="file-man-box">
                                                <div class="file-img-box"><img
                                                            src="{{asset('frontend/images/file-extensions/pdf.svg')}}"
                                                            alt="icon"></div>
                                                <a href="{{str_replace('public', 'storage', asset($articleFile->path))}}"
                                                   class="file-download"><i class="fa fa-download"></i></a>
                                                <div class="file-man-title">
                                                    <h5 class="mb-0 text-overflow">{{$articleFile->name}}</h5>
                                                    <p class="mb-0">
                                                        <small>{{App\Helpers\readeble_file_size(Storage::size($articleFile->path))}}</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- <div class="text-center mt-3">
                                    <button type="button" class="btn btn-outline-danger w-md waves-effect waves-light"><i class="mdi mdi-refresh"></i> Load More Files</button>
                                </div> -->
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <br/>
                    <hr/>
                @endif
            </section>
            <!-- end of Articles -->
            <!-- Share -->
            <section id="share">
                <div class="search-page-top contact-one__form">
                    <div class="contact-one__box">
                        <span>Share This: </span>
                        <div class="contact-one__box-social">
                            <a href="#" class="fab fa-facebook-f" title="Facebook"></a>
                            <a href="#" class="fab fa-twitter" title="Twitter"></a>
                            <a href="#" class="fab fa-pinterest-p" title="Pinterest"></a>
                            <a href="#" class="fab fa-linkedin-in" title="LinkedIn"></a>
                            <a href="#" class="fab fa-whatsapp" title="WhatsApp"></a>
                            <a href="#" class="far fa-envelope" title="Email"></a>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end of Share -->
        </div>
    </section>


    <!-- CTA -->
    @include('frontend.panels.cta')
    <!-- CTA -->
    <!-- Content Area -->

@endsection

@section('vendor-script')
    <script src="{{asset('frontend/js/swiper-bundle.min.js')}}"></script>
@endsection

@section('page-script')
    <script>
        $(document).ready(() => {
            const swiper = new Swiper(".swiper-gallery", {
                slidesPerView: 1,
                spaceBetween: 30,
                keyboard: {
                    enabled: true,
                },
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
        });
        // $('#photo-gallery-slider').owlCarousel({
        //     loop: true,
        //     margin: 10,
        //     nav: true,
        //     responsive: {
        //         0: {
        //             items: 1
        //         },
        //         600: {
        //             items: 3
        //         },
        //         1000: {
        //             items: 5
        //         }
        //     }
        // })
    </script>
@endsection