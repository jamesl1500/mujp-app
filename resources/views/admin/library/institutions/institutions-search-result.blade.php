@foreach($institutions as $institution)
    <button class="dropdown-item" title="Existing institution" style="cursor:not-allowed" type="button">{{$institution->name}}</button>
    <hr>
@endforeach