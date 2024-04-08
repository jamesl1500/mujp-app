@foreach($philanthropists as $philanthropist)
    <tr>
        <input type="hidden" value="{{ $philanthropist->id }}" />
        <td></td>
        <td>{{$philanthropist->firstname}}</td>
        <td>{{$philanthropist->lastname}}</td>
{{--        <td>{{$philanthropist->year_of_birth.'/'.str_pad($philanthropist->month_of_birth,2,'0',STR_PAD_LEFT).'/'.str_pad($philanthropist->date_of_birth,2,'0',STR_PAD_LEFT)}}</td>--}}
        <td>{{\App\Helpers\str_date_format($philanthropist->year_of_birth, $philanthropist->month_of_birth,$philanthropist->date_of_birth)}}</td>
{{--        <td>{{isset($philanthropist->year_of_death) ? $philanthropist->year_of_death.'/'.str_pad($philanthropist->month_of_death,2,'0',STR_PAD_LEFT).'/'.str_pad($philanthropist->date_of_death,2,'0',STR_PAD_LEFT) : 'Alive'}}</td>--}}
        <td>{{isset($philanthropist->year_of_death) ? \App\Helpers\str_date_format($philanthropist->year_of_death, $philanthropist->month_of_death,$philanthropist->date_of_death) : 'Alive'}}</td>
        <td>{{$philanthropist->business ? $philanthropist->business->name : '-'}}</td>
        <td>
{{--            {{$philanthropist->institutions ? $philanthropist->institutions->pluck('institution')->implode('name', ', ') : '-'}}--}}
            {{$philanthropist->strInstitutions()}}
        </td>
        <td>-</td>
        <td>
            <div class="btn-group">
                <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                    <svg data-feather="more-vertical" class="font-small-4"></svg>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{route('philanthropists.edit', $philanthropist->id) }}">
                        <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                        Edit</a>
                    <a class="dropdown-item delete-record"
                       onclick="showDeleteModal({ id: {{ $philanthropist->id}} , name: '{{$philanthropist->firstname . ' ' . $philanthropist->lastname }}'})">
                        <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                        Delete</a>
                </div>
            </div>
        </td>
    </tr>
@endforeach