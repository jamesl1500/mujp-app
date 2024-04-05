@extends('layouts/contentLayoutMaster')

@section('title', 'USER MANAGEMENT')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection


@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
    @include('shared.alert')


    <section class="admin-users-list">
        <a href="{{ route('admin.users.new') }}" class="btn thm-btn mb-1">ADD</a>
        <!-- <button data-toggle="modal" data-target="#modals-slide-in" class="btn thm-btn  mb-1">Add New User</button> -->
        <table id="table-users" class="table">
            <thead class="thead-light">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        @if($user->role_key == 'member')
                            <span data-feather="user" class="text-primary text-truncate align-middle"></span>
                        @elseif ($user->role_key == 'data-entry')
                            <span data-feather="edit-2" class="text-info text-truncate align-middle"></span>
                        @elseif ($user->role_key == 'admin')
                            <span data-feather="slack" class="text-danger text-truncate align-middle"></span>
                        @endif
                        {{!$user->isRoleEmpty() ? $user->role->name : '-'}}
                    </td>
                    <td>
                        {{--                        <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown" aria-expanded="false">--}}
                        {{--                            <span data-feather="more-vertical"></span>--}}
                        {{--                        </a>--}}

                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <input type="hidden" name="id" value="{{$user->id}}"/>
                                <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}"
                                   onclick="//submitForm(event)">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a href="javascript:deleteUser({ id: {{ $user->id}} , fullName: '{{$user->getFullName()}}'})"
                                   class="dropdown-item delete-record">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
            <div class="modal-dialog">
                <div class="add-new-user modal-content pt-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="modal-new-user-title">New User</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <form id="frm-new-user" onsubmit="storeNewUser(event);">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                                <input
                                        type="text"
                                        class="form-control dt-first-name @error('first_name') is-invalid @enderror"
                                        id="user-first-name"
                                        placeholder="Enter User's First Name"
                                        name="first_name"
                                        aria-label="First Name"
                                        aria-describedby="basic-icon-default-firstname"
                                        required
                                />
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                                <input
                                        type="text"
                                        class="form-control dt-last-name @error('last_name') is-invalid @enderror"
                                        id="user-last-name"
                                        placeholder="Enter User's Last Name"
                                        name="last_name"
                                        aria-label="Yor Lastname"
                                        aria-describedby="basic-icon-default-firstname"
                                        required
                                />
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-email">E-Mail</label>
                                <input
                                        type="email"
                                        id="user-email"
                                        class="form-control dt-email @error('email') is-invalid @enderror"
                                        placeholder="Enter User's E-Mail Address"
                                        aria-label="john.doe@example.com"
                                        aria-describedby="basic-icon-default-email2"
                                        name="email"
                                        required
                                />
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-email">Password</label>
                                <input
                                        type="password"
                                        id="user-password"
                                        class="form-control dt-password @error('password') is-invalid @enderror"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-label="password"
                                        aria-describedby="basic-icon-default-password"
                                        name="password"
                                        required
                                />
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-email">Confirm Password</label>
                                <input
                                        type="password"
                                        id="user-password-confirmation"
                                        class="form-control dt-password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-label="password_confirmation"
                                        aria-describedby="basic-icon-default-password_confirmation"
                                        name="password_confirmation"
                                        required
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="user-role">User Role</label>
                                <select name="role" id="user-role" class="form-control" required>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <button type="submit"
                                    class="btn thm-btn mr-1 data-submit">
                                Save
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel
                            </button>
                        </form>
                    </div>


                </div>
            </div>
        </div>

        <div id="deleteModal" class="modal fade modal-danger">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DELETE USER</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                                   id="deletingUserName"></span>?
                    </div>
                    <form method="post" id="deleteModal" action="{{route('admin.users.delete')}}">
                        @csrf
                        <input type="hidden" name="id" id="input-user-id"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">DELETE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>

        let deleteUser = (user) => {
            console.log(user);
            $('#deletingUserName').text(user.fullName);
            $('#input-user-id').val(user.id);
            $('#deleteModal').modal();
        }
        let submitForm = (event) => {
            console.log(event.target);
            // event.preventDefault();
            let closestForm = event.target.closest('form');
            console.log(closestForm);

            // closestForm.submit();
            // form.submit();
        }

        let storeNewUser = (event) => {
            event.preventDefault();
            console.log(event.target);

            let firstName = $('#user-first-name').val();
            let lastName = $('#user-last-name').val();
            let email = $('#user-email').val();
            let password = $('#user-password').val();
            let passwordConfirmation = $('#user-password-confirmation').val();
            let role = $('#user-role').val();

            console.log(firstName);
            console.log(lastName);
            console.log(email);
            console.log(password);
            console.log(passwordConfirmation);
            console.log(role);


            $.ajax({
                url: "{{ route('admin.users.store-with-ajax') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    password: password,
                    password_confirmation: passwordConfirmation,
                },
                success: function (response) {
                    console.log('success', response);
                    location.reload();
                },
                error: function (response) {
                    console.log('error', response.responseJSON.errors);
                    for (let [key, value] of response.responseJSON.errors.password){
                        console.log(key);
                        console.log(value);
                    }
                },
            });
        }

    </script>
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
@endsection


@section('page-script')
    {{-- Page js files --}}
    <script>
        $(document).ready(function () {
            $('#table-users').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });

        });
    </script>
@endsection

