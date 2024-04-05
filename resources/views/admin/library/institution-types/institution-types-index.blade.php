@extends('layouts/contentLayoutMaster')

@section('title', 'INSTITUTION TYPES')

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

    <section id="institutionTypes">
        <a onclick="showAddModal()" class="btn thm-btn  mb-1">ADD</a>

        <!-- InstitutionType List -->
        <table id="table-institutionTypes" class="table">
            <thead class="thead-light">
            <tr>
                <th>INSTITUTION TYPE</th>
                <th>NO. OF RECORDS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($institutionTypes as $institutionType)
                <tr>
                    <td>{{ $institutionType->name }}</td>
                    <td>-</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $institutionType->id }}, name: '{{ $institutionType->name }}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $institutionType->id}} , name: '{{$institutionType->name}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- InstitutionType List END -->

        <!-- Add Modal -->
        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD INSTITUTION TYPE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add InstitutionType -->
                            <section id="add-institutionType">

                                <div class="row">
                                    <!-- InstitutionType name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="institutionType_name">Institution Type</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('institutionType_name') is-invalid @enderror"
                                                        placeholder="Institution Type"
                                                        value="{{old('institutionType_name')}}"
                                                        name="institutionType_name"
                                                        id="institutionType_name"
                                                        oninput="onAddInstitutionTypeNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="spinner-check-institutionType"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-already-exists" class="error mt-1 d-none" role="alert">
                                                Institution type already exists!
                                            </span>
                                            <div id="add-institutionType-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-institutionType-search-result">
                                            </div>
                                            @error('institutionType_name')
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

                        <input type="hidden" name="id" id="input-save-institutionType-id"/>
                        <div class="modal-footer">
                            <button id="button-institutionType-save" type="submit" class="btn thm-btn" disabled>SAVE
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
                        <h4 class="modal-title">DELETE INSTITUTION TYPE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                                         id="deletingInstitutionTypeName"></span>?
                    </div>
                    <form method="post" id="form-delete-institutionType">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-institutionType-id"/>
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
                        <h4 class="modal-title">EDIT INSTITUTION TYPE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-institutionType" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-institutionType-id"/>
                        <div class="modal-body">
                            <!-- Edit Industries -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- InstitutionType name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-institutionType-name">Institution Type</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-institutionType-name') is-invalid @enderror"
                                                        placeholder="Institution Type"
                                                        value=""
                                                        name="input-edit-institutionType-name"
                                                        id="input-edit-institutionType-name"
                                                        oninput="onUpdateInstitutionTypeNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-institutionType"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                Institution type is already exists!
                                            </span>
                                            <div id="edit-institutionType-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-institutionType-search-result">
                                            </div>
                                            @error('input-edit-institutionType-name')
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
                            <button id="button-institutionType-update" type="submit" class="btn thm-btn" disabled>SAVE CHANGES
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

        const institutionTypeNameCheckerUrl = "{{ route('institution-types.search') }}",
            token = "{{ csrf_token() }}",
            institutionTypesDeleteUrl = "{{route('institution-types.destroy','')}}",
            institutionTypesUpdateUrl = "{{route('institution-types.update','')}}";

        const showDeleteModal = (institutionType) => {
            let deleteForm = document.querySelector('#form-delete-institutionType');
            deleteForm.action = `${institutionTypesDeleteUrl}/${institutionType.id}`;
            $('#deletingInstitutionTypeName').text(institutionType.name);
            $('#input-delete-institutionType-id').val(institutionType.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (institutionType) => {
            let updateForm = document.querySelector('#form-update-institutionType');
            updateForm.action = `${institutionTypesUpdateUrl}/${institutionType.id}`;
            $('#input-edit-institutionType-name').val(institutionType.name);
            $('#input-edit-institutionType-id').val(institutionType.id);
            $('#edit-institutionType-search-result').html('');
            $('#span-edit-already-exists').addClass('d-none');
            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#institutionType_name').val('');
            $('#add-institutionType-search-result').html('');
            $('#span-already-exists').addClass('d-none');
            $('#addModal').modal();
        }

        const onAddInstitutionTypeNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-institutionType-save').prop('disabled', true);

            let spinnerCheckInstitutionType = $('#spinner-check-institutionType');
            spinnerCheckInstitutionType.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-institutionType-search-result').html('');
                spinnerCheckInstitutionType.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: institutionTypeNameCheckerUrl,
                    spinnerId: 'spinner-check-institutionType',
                    actionButtonId: 'button-institutionType-save',
                    spanId: 'span-already-exists',
                    searchResultItemId: 'add-institutionType-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        institutionType_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateInstitutionTypeNameInputChanged = (event) => {
            $('#button-institutionType-update').prop('disabled', true);
            $('#edit-spinner-check-institutionType').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: institutionTypeNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-institutionType',
                    actionButtonId: 'button-institutionType-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-institutionType-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        institutionType_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-institutionType-id').val()
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

                    if (response.similarRecords && checkerConfig.data.institutionType_name.length >= 3) {
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
            $('#table-institutionTypes').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection