@foreach($states as $state)
    <tr>
        <td>{{ $state->name }}</td>
        <td>{{ $state->latitude }}</td>
        <td>{{ $state->longitude }}</td>
        <td>
            <div class="btn-group">
                <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                    <svg data-feather="more-vertical" class="font-small-4"></svg>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{route('states.edit', $state->id)}}">
                        <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                        Edit</a>
                    <a class="dropdown-item delete-record"
                       onclick="showDeleteModal({ id: {{ $state->id}} , name: '{{$state->name}}'})">
                        <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                        Delete</a>
                </div>
            </div>
        </td>
    </tr>
@endforeach