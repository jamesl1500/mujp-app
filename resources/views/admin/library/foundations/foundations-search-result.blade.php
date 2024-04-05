@foreach($foundations as $foundation)
    <button class="dropdown-item" title="Existing foundation" style="cursor:not-allowed" type="button">{{$foundation->name}}</button>
    <hr>
@endforeach