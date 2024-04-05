@extends('layouts/contentLayoutMaster')

@section('title', 'INSTITUTION ROLES')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection


@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">

    <style>
        .dropdown-searchbox {
            overflow: auto;
            max-height: 15rem;
        }

        .dropdown-searchbox hr {
            margin: 0px !important;
        }

        .content-header-title {
            border-right: none !important;
        }
    </style>
@endsection

@section('content')
    @include('shared.alert')

    <section id="institutionRoles">
        <a onclick="showAddModal()" class="btn thm-btn  mb-1">ADD</a>

        <!-- InstitutionRole List -->
        <table id="table-institutionRoles" class="table">
            <thead class="thead-light">
            <tr>
                <th>INSTITUTION ROLE</th>
                <th>NO. OF RECORDS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($institutionRoles as $institutionRole)
                <tr>
                    <td>{{ $institutionRole->name }}</td>
                    <td>-</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $institutionRole->id }}, name: '{{ $institutionRole->name }}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $institutionRole->id}} , name: '{{$institutionRole->name}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- InstitutionRole List END -->

        <!-- Add Modal -->
        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD INSTITUTION ROLE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add InstitutionRole -->
                            <section id="add-institutionRole">

                                <div class="row">
                                    <!-- InstitutionRole name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="institutionRole_name">Institution Role</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('institutionRole_name') is-invalid @enderror"
                                                        placeholder="Institution Role"
                                                        value="{{old('institutionRole_name')}}"
                                                        name="institutionRole_name"
                                                        id="institutionRole_name"
                                                        oninput="onAddInstitutionRoleNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="spinner-check-institutionRole"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-already-exists" class="error mt-1 d-none" role="alert">
                                                Institution role already exists!
                                            </span>
                                            <div id="add-institutionRole-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-institutionRole-search-result">
                                            </div>
                                            @error('institutionRole_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Add Industries END -->
                        </div>

                        <input type="hidden" name="id" id="input-save-institutionRole-id"/>
                        <div class="modal-footer">
                            <button id="button-institutionRole-save" type="submit" class="btn thm-btn" disabled>SAVE
                            </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Add Modal END -->

        <!-- Delete Modal -->
        <div id="deleteModal" class="modal fade modal-danger">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DELETE INSTITUTION ROLE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                                         id="deletingInstitutionRoleName"></span>?
                    </div>
                    <form method="post" id="form-delete-institutionRole">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-institutionRole-id"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">REMOVE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Modal END -->

        <!-- Edit Modal -->
        <div id="editModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">EDIT INSTITUTION ROLE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-institutionRole" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-institutionRole-id"/>
                        <div class="modal-body">
                            <!-- Edit Industries -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- InstitutionRole name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-institutionRole-name">Institution Role</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-institutionRole-name') is-invalid @enderror"
                                                        placeholder="Institution Role"
                                                        value=""
                                                        name="input-edit-institutionRole-name"
                                                        id="input-edit-institutionRole-name"
                                                        oninput="onUpdateInstitutionRoleNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-institutionRole"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                Institution role already exists!
                                            </span>
                                            <div id="edit-institutionRole-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-institutionRole-search-result">
                                            </div>
                                            @error('input-edit-institutionRole-name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Edit Industries END -->
                        </div>

                        <div class="modal-footer">
                            <button id="button-institutionRole-update" type="submit" class="btn thm-btn" disabled>SAVE CHANGES
                            </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit Modal END -->
    </section>

    <script>
        let lastTimeOut = null;

        const institutionRoleNameCheckerUrl = "{{ route('institution-roles.search') }}",
            token = "{{ csrf_token() }}",
            institutionRolesDeleteUrl = "{{route('institution-roles.destroy','')}}",
            institutionRolesUpdateUrl = "{{route('institution-roles.update','')}}";

        const showDeleteModal = (institutionRole) => {
            let deleteForm = document.querySelector('#form-delete-institutionRole');
            deleteForm.action = `${institutionRolesDeleteUrl}/${institutionRole.id}`;
            $('#deletingInstitutionRoleName').text(institutionRole.name);
            $('#input-delete-institutionRole-id').val(institutionRole.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (institutionRole) => {
            let updateForm = document.querySelector('#form-update-institutionRole');
            updateForm.action = `${institutionRolesUpdateUrl}/${institutionRole.id}`;
            $('#input-edit-institutionRole-name').val(institutionRole.name);
            $('#input-edit-institutionRole-id').val(institutionRole.id);
            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#institutionRole_name').val('');
            $('#add-institutionRole-search-result').html('');
            $('#span-already-exists').addClass('d-none');
            $('#addModal').modal();
        }

        const onAddInstitutionRoleNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-institutionRole-save').prop('disabled', true);

            let spinnerCheckInstitutionRole = $('#spinner-check-institutionRole');
            spinnerCheckInstitutionRole.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-institutionRole-search-result').html('');
                spinnerCheckInstitutionRole.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: institutionRoleNameCheckerUrl,
                    spinnerId: 'spinner-check-institutionRole',
                    actionButtonId: 'button-institutionRole-save',
                    spanId: 'span-already-exists',
                    searchResultItemId: 'add-institutionRole-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        institutionRole_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateInstitutionRoleNameInputChanged = (event) => {
            $('#button-institutionRole-update').prop('disabled', true);
            $('#edit-spinner-check-institutionRole').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: institutionRoleNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-institutionRole',
                    actionButtonId: 'button-institutionRole-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-institutionRole-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        institutionRole_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-institutionRole-id').val()
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const checkItemExists = (checkerConfig) => {
            const searchResultItem = $('#' + checkerConfig.searchResultItemId);
            const actionButton = $('#' + checkerConfig.actionButtonId);
            const loadingSpinner = $('#' + checkerConfig.spinnerId);
            const existsSpan = $('#' + checkerConfig.spanId);

            loadingSpinner.addClass('d-none');

            $.ajax({
                url: checkerConfig.url,
                type: checkerConfig.method ? checkerConfig.method : 'POST',
                data: checkerConfig.data,
                success: function (response) {
                    switch (response.status) {
                        case 'not-exists' : {
                            actionButton.prop('disabled', false)
                            break;
                        }
                        case 'exists' : {
                            actionButton.prop('disabled', true);
                            existsSpan.removeClass('d-none');
                            break;
                        }
                        default : {
                            actionButton.prop('disabled', false);
                            existsSpan.addClass('d-none');
                        }
                    }

                    loadingSpinner.addClass('d-none');

                    if (response.similarRecords && checkerConfig.data.institutionRole_name.length >= 3) {
                        searchResultItem.html(response.similarRecords);
                    }
                    else {
                        searchResultItem.html('');
                    }
                },
                error: function (response) {
                    console.log('error', response);
                },
            });
        }
    </script>

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {
            $('#table-institutionRoles').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection