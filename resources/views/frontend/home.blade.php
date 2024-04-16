@extends('layouts.frontend.master')

@section('title')
    Home
@endsection

@section('page-style')
    <style>
        .content-box h2,h3 {
            text-transform: none !important;
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/5pwiuoduxtz2fuazw1kk7xpcal9ytfkla457ygji53oozf3f/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('content')
    <?php 
    // If loggewd in user is admin
    if (Auth::check() && Auth::user()->role_key == "admin") {
        ?>
            <script>
                tinymce.init({
                    selector: '.editable',
                    inline: true,
                    setup: function(editor) {
                       // Add event listener for keydown event
                        editor.on('keydown', function(e) {
                        // Get selectors data
                        var htId = editor.getElement().getAttribute('data-htid');

                        // Check if Enter key was pressed (key code 13)
                        if (e.keyCode === 13) {
                            // Prevent default Enter behavior (new line)
                            e.preventDefault();
                            
                            // Get the content from the editor
                            var content = editor.getContent();
                            
                            // Make AJAX call to your server
                            $.ajax({
                            url: '{{ route("homepage_text.update") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                htid: htId,
                                content: content
                            },
                            success: function(response) {
                                // Handle success response
                                alert('Content saved successfully!');
                                console.log('Content saved successfully:', response);
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                console.error('Error saving content:', error);
                            }
                            });
                        }
                        });
                    }
                });
            </script>
        <?php
    }
            // Get first record in 'homepage_text' table
            $homepage_text = DB::table('homepage_text')->first();
    ?>
    <section class="banner-section banner-section__home-two">
        <div class="banner-carousel thm__owl-carousel owl-theme owl-carousel" data-options='{"loop": true, "items": 1, "margin": 0, "dots": false, "nav": true, "animateOut": "fadeOut", "animateIn": "fadeIn", "active": true, "smartSpeed": 3000, "autoplay": true, "autoplayTimeout": 6000, "autoplayHoverPause": false}'>
            <!-- Slide Item -->
            <div class="slide-item">
                <div class="image-layer lazy-image" style="background-image: url('{{asset('frontend/images/banner/slide-item-1.jpg')}}');"></div>
                <div class="container">
                    <div class="content-box text-center">
                        <h3>Most Detailed Philanthropists Data</h3>
                        <h2>Over 1,000,000 records <br />and growing!</h2>
                        <div class="btn-box">
                            <a href="{{route('frontend.about')}}" class="thm-btn btn-style-one">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide Item -->
            <div class="slide-item">
                <div class="image-layer lazy-image" style="background-image: url('{{asset('frontend/images/banner/slide-item-2.jpg')}}');"></div>
                <div class="container">
                    <div class="content-box text-center">
                        <h3>Museum of Jewish Philanthropists</h3>
                        <h2>Discover the Treasures of<br/>Jewish Philanthropists</h2> <!-- TODO: -->
                        <div class="btn-box">
                            <a href="{{route('frontend.philanthropists.index')}}" class="thm-btn btn-style-one">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Banner Section -->

    <section class="about-two" style="background-image: url('{{asset('frontend/images/backgrounds/event-bg-1-1.jpg')}}'); background-size: cover !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-two__content">
                        <div class="block-title">
                            <p class="editable" data-htid="first_section_small_pretitle"><?php echo $homepage_text->first_section_small_pretitle; ?></p>
                            <h3 class="editable" data-htid="first_section_main_title" ><?php echo $homepage_text->first_section_main_title; ?></h3>
                        </div>
                        <p class="about-two__highlight editable" data-htid="first_section_small_subtitle">
                            <?php echo $homepage_text->first_section_small_subtitle; ?>
                        </p>
                        <p class="editable" data-htid="first_section_main_text">
                            <?php echo $homepage_text->first_section_main_text; ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about-two__image">
                        <img src="{{asset('frontend/images/resources/about-2-1.jpg')}}" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.panels.cta')


    <section class="collection-two">
        <div class="container-fluid">
            <div class="block-title-two text-center">
                <p>Philanthropists</p>
                <h3>Explore The Philanthropists</h3>
            </div><!-- /.block-title-two -->
            <div class="collection-two__carousel shadowed__carousel thm__owl-carousel owl-carousel owl-theme" data-options='{"loop": true, "margin": 0, "autoplay": true, "autoplayTimeout": 5000, "autoplayHoverPause": true, "items": 5, "smartSpeed": 700, "dots": false, "nav": true, "responsive": {
		    		"1920": { "items": 5},
		    		"1440": { "items": 4},
		    		"1199": { "items": 4},
		    		"991": { "items": 3},
		    		"767": { "items": 2},
		    		"575": { "items": 2},
		    		"480": { "items": 2},
		    		"0": { "items": 1}
		    	}
		    }'>

                @foreach($philanthropists as $philanthropist)
                    <div class="item">
                        <div class="collection-two__single">
                            <div class="collection-two__image">
                                <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}">
                                    <img src="{{$philanthropist->profileImage->first() ? str_replace('public', 'storage', asset($philanthropist->profileImage->first()->path)): asset('frontend/images/avatar/mujp-no-avatar.svg')}}" alt="">
                                </a>
                            </div>
                            <div class="collection-two__content">
                                <h3><a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}">{{$philanthropist->firstname . ' ' . $philanthropist->lastname}}</a></h3>
                                <p style="text-transform: none !important;">{{$philanthropist->getBirthDeathStr()}}</p>
                                <br>
                            </div>
                        </div>
                    </div>
                @endforeach

{{--                <div class="item">--}}
{{--                    <div class="collection-two__single">--}}
{{--                        <div class="collection-two__image">--}}
{{--                            <a href="#">--}}
{{--                                <img src="{{asset('frontend/images/avatar/mujp-philanthropist-portrait.jpg')}}" alt="">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div class="collection-two__content">--}}
{{--                            <h3><a href="#">Paul Baerwald</a></h3>--}}
{{--                            <p>Education Industry</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <div class="collection-two__single">--}}
{{--                        <div class="collection-two__image">--}}
{{--                            <a href="#">--}}
{{--                                <img src="{{asset('frontend/images/avatar/mujp-woman-philanthropist-portrait.jpg')}}" alt="">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div class="collection-two__content">--}}
{{--                            <h3><a href="#">Rebecca Gratz</a></h3>--}}
{{--                            <p>Education Industry</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <div class="collection-two__single">--}}
{{--                        <div class="collection-two__image">--}}
{{--                            <a href="#">--}}
{{--                                <img src="{{asset('frontend/images/avatar/no_avatar.jpg')}}" alt="">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div class="collection-two__content">--}}
{{--                            <h3><a href="#">Catherine Alexandria</a></h3>--}}
{{--                            <p>Textile Industry</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}


            </div>
        </div>
    </section>

    <section class="featured-collection">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="featured-collection__left">
                        <div class="featured-collection__image">
                            <img src="{{asset('frontend/images/resources/featured-philanthropists-images-1-1.jpg')}}" alt="">
                        </div>
                        <p class="editable" data-htid="third_section_main_text"><?php echo $homepage_text->third_section_main_text; ?></p>
                        <a href="{{route('frontend.philanthropists.index')}}" class="thm-btn featured-collection__btn">Explore Philanthropists</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="featured-collection__right">
                        <div class="block-title">
                            <p class="editable" data-htid="third_section_small_pretitle"><?php echo $homepage_text->third_section_small_pretitle; ?></p>
                            <h3 class="editable" data-htid="third_section_main_title" ><?php echo $homepage_text->third_section_main_title; ?></h3>
                        </div>
                        <p class="editable" data-htid="third_section_small_subtitle"><?php echo $homepage_text->third_section_small_subtitle; ?></p>
                        <div class="featured-collection__image">
                            <img src="{{asset('frontend/images/resources/featured-philanthropists-images-2-2.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="brand-one">
        <div class="container">
            <div class="brand-one__carousel thm__owl-carousel owl-carousel owl-theme" data-options='{"items": 5, "margin": 150, "smartSpeed": 700, "loop": true, "autoplay": true, "autoplayTimeout": 5000, "autoplayHoverPause": false, "nav": false, "dots": false, "responsive": {"0": { "margin": 20, "items": 2 }, "575": { "margin": 30, "items": 3 },"767": { "margin": 40, "items": 4 },   "991": { "margin": 70, "items": 4 }, "1199": { "margin": 150, "items": 5 } } }'>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-1.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-2.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-3.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-4.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-5.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-1.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-2.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-3.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-4.png')}}" alt="">
                </div>
                <div class="item">
                    <img src="{{asset('frontend/images/brand/brand-1-5.png')}}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection