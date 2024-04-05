@foreach($institutionTypes as $institutionType)
    <button class="dropdown-item" title="Existing institution type" style="cursor:not-allowed" type="button">{{$institutionType->name}}</button>
    <hr>
@endforeach
