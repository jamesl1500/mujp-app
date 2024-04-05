@foreach($philanthropists as $philanthropist)
    <!-- Philanthropist Item -->
    <div class="philanthropists-item" style="display: none">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-3 philanthropists-image">
                    <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}">
                        <img src="{{$philanthropist->profileImage->first() ? str_replace('public', 'storage', asset($philanthropist->profileImage->first()->path)): asset('frontend/images/avatar/mujp-no-avatar.svg')}}"
                             alt="">
                    </a>
                </div>
                <div class="col-md-9 philanthropists-content">
                    <h3>
                        <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}">{{$philanthropist->firstname . ' ' . $philanthropist->lastname}}</a>
                        @can('isMember')
                            <i class="{{isset($philanthropist->isFavoriteForUser) && $philanthropist->isFavoriteForUser->count() > 0 ? 'favorite-selected fas' : 'far'}} fa-star text-center favorite-icon"
                               onclick="favoriteToggleHandler(event)"
                               data-toggle="tooltip"
                               title="{{isset($philanthropist->isFavoriteForUser) && $philanthropist->isFavoriteForUser->count() > 0 ? 'Remove from Favorites' : 'Add to Favorites'}}"
                               data-philanthropist-id="{{$philanthropist->id}}"></i>
                        @endcan
                    </h3>
                    <strong>{{$philanthropist->getBirthDeathStr()}}</strong>
                    <hr>
                    <div></div>
                    {{--                    <span class="date-of-birth"><i class="far fa-calendar"></i> Date of Birth: {{\App\Helpers\str_date_format($philanthropist->year_of_birth, $philanthropist->month_of_birth,$philanthropist->date_of_birth)}}</span><span--}}
                    {{--                            class="date-of-death"><i class="far fa-calendar"></i>  Date of Death: {{isset($philanthropist->year_of_death) ? \App\Helpers\str_date_format($philanthropist->year_of_death, $philanthropist->month_of_death,$philanthropist->date_of_death) : 'Alive'}}</span><br>--}}

                    <div class="row">
                        @if(isset($philanthropist->city_of_birth))
                            <div class="col-12  @if($philanthropist->business) col-md-6 @endif "><i class="fas fa-map-marker-alt"></i> Country of
                                Birth: {{isset($philanthropist->city_of_birth) ? $philanthropist->city->state->country->name : 'Unknown'}}
                            </div>
                        @endif


                        @if($philanthropist->business)
                            <div class="col-12 col-md-6">
                        <span><i class="fa fa-briefcase"></i>
                            Industry:
                            @if($philanthropist->business->industry)
                                {{$philanthropist->business->industry->name}}
                            @elseif($philanthropist->business->industry_other)
                                {{$philanthropist->business->industry_other}}
                            @endif
                        </span>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex">
                        <div class="content-button" style="padding-right: 8px">
                            <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}"
                               class="thm-btn event-one__btn">Details</a>
                        </div>
                        @can('isAdmin')
                            <div class="content-button">
                                <a href="{{route('philanthropists.edit', $philanthropist->id)}}" target="_blank"
                                   class="thm-btn event-one__btn">Edit</a>
                            </div>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Philanthropist Item -->
@endforeach