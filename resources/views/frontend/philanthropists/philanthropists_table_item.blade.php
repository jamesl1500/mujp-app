@foreach($philanthropists as $philanthropist)
    <tr>
        <td class="td-image">
            <img src="{{$philanthropist->profileImage->first() ? str_replace('public', 'storage', asset($philanthropist->profileImage->first()->path)): asset('frontend/images/avatar/mujp-no-avatar.svg')}}"
                 alt="" width="48">
        </td>
        <td class="td-name">{{$philanthropist->firstname. ' ' . $philanthropist->lastname}} @can('isMember')
                <i class="{{isset($philanthropist->isFavoriteForUser) && $philanthropist->isFavoriteForUser->count() > 0 ? 'favorite-selected fas' : 'far'}} fa-star text-center favorite-icon"
                   onclick="favoriteToggleHandler(event)" data-philanthropist-id="{{$philanthropist->id}}"
                   data-toggle="tooltip"
                   title="{{isset($philanthropist->isFavoriteForUser) && $philanthropist->isFavoriteForUser->count() > 0 ? 'Remove from Favorites' : 'Add to Favorites'}}"></i>
            @endcan</td>
        <td class="td-date-of-birth">{{$philanthropist->getDateOfBirthFormatted() != '' ? $philanthropist->getDateOfBirthFormatted() : '----'}}</td>
        <td class="td-date-of-death">{{$philanthropist->getDateOfDeathFormatted() != '' ? $philanthropist->getDateOfDeathFormatted() : '----'}}</td>
        <td class="td-country-of-birth">{{isset($philanthropist->city_of_birth) ? $philanthropist->city->state->country->name  : '----'}}</td>
        <td class="td-industry"> @if($philanthropist->business)
                @if($philanthropist->business->industry)
                    {{$philanthropist->business->industry->name}}
                @elseif($philanthropist->business->industry_other)
                    {{$philanthropist->business->industry_other}}
                @endif
            @else
                ----
            @endif
        </td>
        <td class="td-btn-detail">
            <a href="{{route('frontend.philanthropist.show-with-slug', ['id' => $philanthropist->id, 'slug' => \Illuminate\Support\Str::slug($philanthropist->firstname.' '.$philanthropist->lastname)])}}"
               class="btn thm-btn-sm w-100" style="color: white;">Detail</a>
        </td>
    </tr>
@endforeach