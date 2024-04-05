@extends('layouts/contentLayoutMaster')

@section('title', 'INSTITUTIONS')

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

    <section id="institutions">
        <a onclick="showAddModal()" class="btn thm-btn mb-1">ADD</a>

        <!-- Institution List -->
        <table id="table-institutions" class="table">
            <thead class="thead-light">
            <tr>
                <th>INSTITUTION NAME</th>
                <th>NO. OF RECORDS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($institutions as $institution)
                <tr>
                    <td>{{ $institution->name }}</td>
                    <td>-</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $institution->id }}, name: '{{ str_replace('\'', ' ', $institution->name )}}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $institution->id}} ,  name: '{{ str_replace('\'', ' ', $institution->name )}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Institution List END -->

        <!-- Add Modal -->
        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD INSTITUTION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add Institution -->
                            <section id="add-institution">

                                <div class="row">
                                    <!-- Institution name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="institution_name">Institution Name</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('institution_name') is-invalid @enderror"
                                                        placeholder="Institution Name"
                                                        value="{{old('institution_name')}}"
                                                        name="institution_name"
                                                        id="institution_name"
                                                        oninput="onAddInstitutionNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="spinner-check-institution"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-already-exists" class="error mt-1 d-none" role="alert">
                                                Institution name already exists!
                                            </span>
                                            <div id="add-institution-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-institution-search-result">
                                            </div>
                                            @error('institution_name')
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

                        <input type="hidden" name="id" id="input-save-institution-id"/>
                        <div class="modal-footer">
                            <button id="button-institution-save" type="submit" class="btn thm-btn" disabled>SAVE
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
                        <h4 class="modal-title">DELETE INSTITUTION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                                         id="deletingInstitutionName"></span>?
                    </div>
                    <form method="post" id="form-delete-institution">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-institution-id"/>
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
                        <h4 class="modal-title">EDIT INSTITUTION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-institution" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-institution-id"/>
                        <div class="modal-body">
                            <!-- Edit Industries -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- Institution name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-institution-name">Institution Name</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-institution-name') is-invalid @enderror"
                                                        placeholder="Institution Name"
                                                        value=""
                                                        name="input-edit-institution-name"
                                                        id="input-edit-institution-name"
                                                        oninput="onUpdateInstitutionNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-institution"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                Institution name already exists!
                                            </span>
                                            <div id="edit-institution-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-institution-search-result">
                                            </div>
                                            @error('input-edit-institution-name')
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
                            <button id="button-institution-update" type="submit" class="btn thm-btn" disabled>SAVE CHANGES
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

        const institutionNameCheckerUrl = "{{ route('institutions.search') }}",
            token = "{{ csrf_token() }}",
            institutionsDeleteUrl = "{{route('institutions.destroy','')}}",
            institutionsUpdateUrl = "{{route('institutions.update','')}}";

        const showDeleteModal = (institution) => {
            let deleteForm = document.querySelector('#form-delete-institution');
            deleteForm.action = `${institutionsDeleteUrl}/${institution.id}`;
            $('#deletingInstitutionName').text(institution.name);
            $('#input-delete-institution-id').val(institution.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (institution) => {
            let updateForm = document.querySelector('#form-update-institution');
            updateForm.action = `${institutionsUpdateUrl}/${institution.id}`;
            $('#input-edit-institution-name').val(institution.name);
            $('#input-edit-institution-id').val(institution.id);
            $('#edit-institution-search-result').html('');
            $('#span-edit-already-exists').addClass('d-none');
            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#institution_name').val('');
            $('#add-institution-search-result').html('');
            $('#span-already-exists').addClass('d-none');
            $('#addModal').modal();
        }

        const onAddInstitutionNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-institution-save').prop('disabled', true);

            let spinnerCheckInstitution = $('#spinner-check-institution');
            spinnerCheckInstitution.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-institution-search-result').html('');
                spinnerCheckInstitution.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: institutionNameCheckerUrl,
                    spinnerId: 'spinner-check-institution',
                    actionButtonId: 'button-institution-save',
                    spanId: 'span-already-exists',
                    searchResultItemId: 'add-institution-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        institution_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateInstitutionNameInputChanged = (event) => {
            $('#button-institution-update').prop('disabled', true);
            $('#edit-spinner-check-institution').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: institutionNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-institution',
                    actionButtonId: 'button-institution-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-institution-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        institution_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-institution-id').val()
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

                    if (response.similarRecords && checkerConfig.data.institution_name.length >= 3) {
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
            $('#table-institutions').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection