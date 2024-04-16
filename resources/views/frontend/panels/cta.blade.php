<?php
    // Get first record in 'homepage_text' table
    $homepage_text = DB::table('homepage_text')->first();
?>

<section class="cta-one" style="background-image: url({{asset('frontend/images/shapes/cta-bg-1-1.jpg')}});">
    <div class="container text-center">
        <h3><?php echo $homepage_text->second_section_main_title; ?></h3>
        <p><?php echo $homepage_text->second_section_subtitle; ?></p>
        <div class="cta-one__btn-block">
            <a href="#" class="thm-btn cta-one__btn-one">Contact Us</a>
            <a href="{{ route('frontend.charity') }}" class="thm-btn cta-one__btn-one">Charity</a>
            <a href="/donate" class="thm-btn cta-one__btn-two">Donate</a>
        </div>
    </div>
</section>