@foreach($relationTypes as $relationType)
    <button class="dropdown-item" title="Existing relation type" style="cursor:not-allowed" type="button">{{$relationType->name}}</button>
    <hr>
@endforeach