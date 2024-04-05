@foreach($similarRecords as $similarRecord)
    <button class="dropdown-item" style="cursor:not-allowed" type="button">{{$similarRecord[$column]}}</button>
    <hr>
@endforeach