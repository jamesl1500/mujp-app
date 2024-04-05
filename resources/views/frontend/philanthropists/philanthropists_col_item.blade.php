@foreach($philanthropists as $philanthropist)
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="blog-one__single philanthropists-item" style="display: none">
            <div class="blog-one__image philanthropists-image">
                <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}">
                    <img src="{{$philanthropist->profileImage->first() ? str_replace('public', 'storage', asset($philanthropist->profileImage->first()->path)): asset('frontend/images/avatar/mujp-no-avatar.svg')}}"
                         alt="">
                </a>
            </div>
            <div class="blog-one__content philanthropists-content">
                <h3>
                    <a href="{{route('frontend.philanthropist.show', $philanthropist->id)}}">{{$philanthropist->firstname. ' ' . $philanthropist->lastname}}</a>
                    @can('isMember')
                        <i class="{{isset($philanthropist->isFavoriteForUser) && $philanthropist->isFavoriteForUser->count() > 0 ? 'favorite-selected fas' : 'far'}} fa-star text-center favorite-icon"
                           onclick="favoriteToggleHandler(event)" data-philanthropist-id="{{$philanthropist->id}}"
                           data-toggle="tooltip"
                           title="{{isset($philanthropist->isFavoriteForUser) && $philanthropist->isFavoriteForUser->count() > 0 ? 'Remove from Favorites' : 'Add to Favorites'}}"></i>
                    @endcan
                </h3>
                <strong>{{$philanthropist->getBirthDeathStr()}}</strong>
                <hr>
                {{--                <span class="date-of-birth"><i--}}
                {{--                            class="far fa-calendar"></i> Date of Birth: {{\App\Helpers\str_date_format($philanthropist->year_of_birth, $philanthropist->month_of_birth,$philanthropist->date_of_birth)}}</span><br>--}}
                {{--                <span class="date-of-death"><i--}}
                {{--                            class="far fa-calendar"></i>  Date of Death: {{isset($philanthropist->year_of_death) ? \App\Helpers\str_date_format($philanthropist->year_of_death, $philanthropist->month_of_death,$philanthropist->date_of_death) : 'Alive'}}</span><br>--}}

                @if(isset($philanthropist->city_of_birth))
                    <i class="fas fa-map-marker-alt"></i> Country of
                    Birth: {{isset($philanthropist->city_of_birth) ? $philanthropist->city->state->country->name  : 'Unknown'}}
                    </br>
                @else
                @endif

                @if($philanthropist->business)
                    <i class="fa fa-briefcase"></i>
                    Industry:
                    @if($philanthropist->business->industry)
                        {{$philanthropist->business->industry->name}}
                    @elseif($philanthropist->business->industry_other)
                        {{$philanthropist->business->industry_other}}
                    @endif
                @else

                @endif

                @if((isset($philanthropist->city_of_birth) && !$philanthropist->business) || (!isset($philanthropist->city_of_birth) && $philanthropist->business) || isset($philanthropist->city_of_birth) && $philanthropist->business)
                    <hr>
                @endif

                <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}"
                   class="blog-one__link">View Detail</a>
                @can('isAdmin')
                    <a href="{{route('philanthropists.edit', $philanthropist->id)}}"
                       class="blog-one__link ml-1" target="_blank">Edit</a>
                @endcan

            </div>
        </div>
    </div>
@endforeach