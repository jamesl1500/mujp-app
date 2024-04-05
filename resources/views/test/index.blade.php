<div>
    @php
        $configData = Helper::applClasses();
$configData['theme'] = 'dark';
$configData['layoutTheme'] = 'dark';
    @endphp
    @include('panels.navbar')
    {{--    @include('panels/sidebar')--}}
    @include('panels/styles')
    @include('panels/scripts')

    @foreach($users as $user)

        @if($loop->first)
            ......................
        @endif
        <h2 style="color: @if($loop->even)red @else blue @endif">{{$user->first_name}}</h2>
        @if($loop->last)
            ......................
        @endif
    @endforeach
</div>

<script>
    const userList = @json($users);
    for (const user of userList) {
        console.log(user.first_name + ' ' + user.last_name);
    }
    console.log(userList);
</script>