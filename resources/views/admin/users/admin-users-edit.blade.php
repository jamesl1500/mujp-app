@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <!-- Account Tab starts -->

                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                    @include('shared.alert')
                    <!-- users edit account form start -->
                        <form method="POST" action="{{route('admin.users.update')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}"/>
                            <!-- users edit media object start -->
                            <div class="media mb-2">
                                <img
                                        src="{{ !empty($user->profile_image) ? asset('/storage/profile-images/'.$user->profile_image) : asset('/images/avatars/blank-image.png') }}"
                                        alt="users avatar"
                                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer @error('profile_image') is-invalid @enderror"
                                        height="90"
                                        width="90"
                                />
                                <div class="media-body mt-50">
                                    <h4>{{ $user->getFullName()  }}</h4>
                                    <div class="col-12 d-flex mt-1 px-0">
                                        <label class="btn thm-btn mr-75 mb-0" for="change-picture">
                                            <span class="d-none d-sm-block">Change</span>
                                            <input
                                                    name="profile_image"
                                                    class="form-control"
                                                    type="file"
                                                    id="change-picture"
                                                    hidden
                                                    accept="image/png, image/jpeg, image/jpg"
                                            />
                                            <span class="d-block d-sm-none">
                    <i class="mr-0" data-feather="edit"></i>
                  </span>
                                        </label>
                                        <button class="btn btn-outline-secondary d-none d-sm-block" onclick="reloadPage(event)">Remove</button>
                                        <button class="btn btn-outline-secondary d-block d-sm-none">
                                            <i class="mr-0" data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('profile_image')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <!-- users edit media object ends -->
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- First Name -->
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input
                                                type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                placeholder="First Name"
                                                value="{{$user->first_name}}"
                                                name="first_name"
                                                id="first_name"
                                                required
                                        />
                                        @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Last Name -->
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input
                                                type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                placeholder="Last Name"
                                                value="{{$user->last_name}}"
                                                name="last_name"
                                                id="last_name"
                                                required
                                        />
                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-4">
                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input
                                                type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email"
                                                value="{{$user->email}}"
                                                name="email"
                                                id="email"
                                                required
                                        />
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Role -->
                                    <div class="form-group">
                                        <label class="d-block mb-1">Role</label>
                                        @foreach($roles as $role)
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="{{ $role->id }}" name="role"
                                                       value="{{ $role->key }}"
                                                       {{ $role->key == $user->role_key ? 'checked' : '' }}
                                                       class="custom-control-input @error('role') is-invalid @enderror"/>
                                                <label class="custom-control-label"
                                                       for="{{ $role->id }}">{{ $role->name }}</label>
                                            </div>
                                        @endforeach
                                        @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-4">
                                    <!-- Password -->
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                value=""
                                                name="password"
                                                id="password"
                                        />
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Password Confirmation -->
                                    <div class="form-group">
                                        <label for="password-confirmation">Confirm Password</label>
                                        <input
                                                type="password"
                                                class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                value=""
                                                name="password_confirmation"
                                                id="password-confirmation"
                                        />
                                    </div>
                                </div>


                                <!-- Actions -->
                                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                    <button type="submit" class="btn thm-btn mb-1 mb-sm-0 mr-0 mr-sm-1">SAVE CHANGES
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">RESET</button>
                                </div>
                            </div>
                        </form>
                        <!-- users edit account form ends -->
                    </div>
                    <!-- Account Tab ends -->
                </div>
            </div>
        </div>
    </section>
    <!-- users edit ends -->
@endsection


@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/components/components-navs.js')) }}"></script>
    <script>
        let reloadPage = (event) => {
            event.preventDefault();
            location.reload();
        }
    </script>
@endsection