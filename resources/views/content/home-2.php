@extends('layouts/front-end/frontend-layout-master')

@section('title', 'Home')

@section('content')
    <!-- Card Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">@can('isMember')
                    Member access
                @endcan
                @can('isDataEntry')
                    Data entry access
                @endcan
                @can('isAdmin')
                    Admin access
                @endcan
                🚀</h4>
        </div>
        <div class="card-body">
            <div class="card-text">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vitae dignissim lacus. Aliquam tellus
                    metus, ullamcorper vel lectus at, auctor viverra erat. Nullam ornare tristique quam, sed sodales
                    neque gravida quis. Sed cursus leo quis mi iaculis hendrerit. Sed efficitur diam sit amet velit
                    euismod auctor. Curabitur aliquam suscipit pretium. Nullam posuere, tellus quis iaculis ullamcorper,
                    lorem lacus condimentum velit, at porta lectus libero ut mi. Aliquam dictum, nulla et dignissim
                    hendrerit, nulla nunc feugiat magna, non pharetra ligula dolor a elit. Praesent ullamcorper gravida
                    arcu, in ornare mauris condimentum et. Fusce sit amet sapien augue. Vivamus at diam vel dolor
                    porttitor dapibus in sit amet purus. Morbi at luctus lacus. Morbi a enim sodales, mattis lorem eu,
                    eleifend libero.
                </p>
            </div>
        </div>
    </div>
    <!--/ Card Content -->

@endsection
