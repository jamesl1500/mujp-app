<!-- Modal -->
<div class="topbar-modal modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Submit a Philanthropist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                You must be logged in to submit a philanthropist.
            </div>
            <div class="modal-footer">
                <a href="{{ route('login') }}" class="topbar-modal__navlinks" target="_blank">Login</a>
                <span>|</span>
                <a href="{{ route('register') }}" class="topbar-modal__navlinks" target="_blank">Sign Up</a>
            </div>
        </div>
    </div>
</div>
<div class="topbar-one">
    <div class="container">
        <div class="topbar-one__left">
            <p>Largest Database of Jewish Philanthropists</p>
        </div>
        <div class="topbar-one__right">
            <a target="_blank" href="@can('isAdmin') {{route('philanthropists.add')}} @endcan @can('isMember') {{route('member.submit')}} @endcan" @guest onclick="event.preventDefault();$('.topbar-modal').modal();" @endguest>Submit a Philanthropist</a>
            @guest
                <a href="{{ route('login') }}" target="_blank">Login</a>
                <a href="{{ route('register') }}" target="_blank">Sign Up</a>
            @else
                <a href="#" style="margin-right: 15px"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
            {{--            <a href="tel:(555)-555-5500"><i class="fa fa-phone-alt"></i> (555) 555-5500</a>--}}
            <a href="{{route('frontend.donate')}}" class="thm-btn topbar__btn">Donate</a>
            {{--            <a href="{{route('frontend.contact')}}" class="thm-btn topbar__btn">Contact Now!</a>--}}
        </div>
    </div>
</div>
<nav class="main-nav-one stricky">
    <div class="container">
        <div class="inner-container">
            <div class="logo-box">
                <a href="/">
                    <img src="{{asset('frontend/images/museum-of-jewish-philanthropists-logo.png')}}" alt=""
                         width="260">
                </a>
                <a href="#" class="side-menu__toggler"><i class="muzex-icon-menu"></i></a>
            </div>
            <div class="main-nav__main-navigation">
                <ul class="main-nav__navigation-box">
                    <li><a href="{{route('frontend.home')}}">Home</a></li>
                    <li><a href="{{route('frontend.philanthropists.index')}}">Philanthropists</a></li>
                    {{--                    <li class="dropdown">--}}
                    {{--                        <a href="{{route('frontend.philanthropists.index')}}">Philanthropists</a>--}}
                    {{--                        <ul>--}}
                    {{--                            <li><a href="{{route('frontend.philanthropists.index', ['view' => 'column'])}}">Column View</a></li>--}}
                    {{--                            <li><a href="{{route('frontend.philanthropists.index', ['view' => 'list'])}}">List View</a></li>--}}
                    {{--                        </ul>--}}
                    {{--                    </li>--}}
                    <li><a href="{{route('frontend.about')}}">About</a></li>
                    <li><a href="{{route('frontend.donate')}}">Donate</a></li>
                    <li><a href="{{route('frontend.contact')}}">Contact</a></li>
                </ul>
            </div>
            <div class="main-nav__right">
                <a href="#" class="search-popup__toggler"><i class="muzex-icon-search"></i></a>
                <a class="sidemenu-icon side-content__toggler" href="#"><i class="muzex-icon-menu"></i></a>
            </div>
        </div>
    </div>
</nav>