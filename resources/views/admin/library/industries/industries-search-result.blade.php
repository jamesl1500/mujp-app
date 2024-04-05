@foreach($industries as $industry)
    <button class="dropdown-item" title="Existing industry" style="cursor:not-allowed" type="button">{{$industry->name}}</button>
    <hr>
@endforeach