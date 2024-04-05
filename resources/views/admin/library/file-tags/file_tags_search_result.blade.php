@foreach($fileTags as $fileTag)
    <button class="dropdown-item" title="Existing file tag" style="cursor:not-allowed" type="button">{{$fileTag->name}}</button>
    <hr>
@endforeach