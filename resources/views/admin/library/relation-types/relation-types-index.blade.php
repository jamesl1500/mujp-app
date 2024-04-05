@extends('layouts/contentLayoutMaster')

@section('title', 'RELATIONSHIP TYPES')

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

    <section id="relationTypes">
        <a onclick="showAddModal()" class="btn thm-btn  mb-1">ADD</a>

        <!-- RelationType List -->
        <table id="table-relationTypes" class="table">
            <thead class="thead-light">
            <tr>
                <th>RELATIONSHIP TYPE</th>
                <th>NO. OF RECORDS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($relationTypes as $relationType)
                <tr>
                    <td>{{ $relationType->name }}</td>
                    <td>-</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $relationType->id }}, name: '{{ $relationType->name }}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $relationType->id}} , name: '{{$relationType->name}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- RelationType List END -->

        <!-- Add Modal -->
        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD RELATIONSHIP TYPE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add RelationType -->
                            <section id="add-relationType">

                                <div class="row">
                                    <!-- RelationType name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="relationType_name">Relationship Type</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('relationType_name') is-invalid @enderror"
                                                        placeholder="Relationship Type"
                                                        value="{{old('relationType_name')}}"
                                                        name="relationType_name"
                                                        id="relationType_name"
                                                        oninput="onAddRelationTypeNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="spinner-check-relationType"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-already-exists" class="error mt-1 d-none" role="alert">
                                                Relationship type already exists!
                                            </span>
                                            <div id="add-relationType-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-relationType-search-result">
                                            </div>
                                            @error('relationType_name')
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

                        <input type="hidden" name="id" id="input-save-relationType-id"/>
                        <div class="modal-footer">
                            <button id="button-relationType-save" type="submit" class="btn thm-btn" disabled>SAVE
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
                        <h4 class="modal-title">DELETE RELATIONSHIP TYPE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                                         id="deletingRelationTypeName"></span>?
                    </div>
                    <form method="post" id="form-delete-relationType">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-relationType-id"/>
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
                        <h4 class="modal-title">EDIT RELATIONSHIP TYPE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-relationType" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-relationType-id"/>
                        <div class="modal-body">
                            <!-- Edit Industries -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- RelationType name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-relationType-name">Relationship Type</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-relationType-name') is-invalid @enderror"
                                                        placeholder="Relationship Type"
                                                        value=""
                                                        name="input-edit-relationType-name"
                                                        id="input-edit-relationType-name"
                                                        oninput="onUpdateRelationTypeNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-relationType"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                Relationship type already exists!
                                            </span>
                                            <div id="edit-relationType-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-relationType-search-result">
                                            </div>
                                            @error('input-edit-relationType-name')
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
                            <button id="button-relationType-update" type="submit" class="btn thm-btn" disabled>SAVE CHANGES
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

        const relationTypeNameCheckerUrl = "{{ route('relation-types.search') }}",
            token = "{{ csrf_token() }}",
            relationTypesDeleteUrl = "{{route('relation-types.destroy','')}}",
            relationTypesUpdateUrl = "{{route('relation-types.update','')}}";

        const showDeleteModal = (relationType) => {
            let deleteForm = document.querySelector('#form-delete-relationType');
            deleteForm.action = `${relationTypesDeleteUrl}/${relationType.id}`;
            $('#deletingRelationTypeName').text(relationType.name);
            $('#input-delete-relationType-id').val(relationType.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (relationType) => {
            let updateForm = document.querySelector('#form-update-relationType');
            updateForm.action = `${relationTypesUpdateUrl}/${relationType.id}`;
            $('#input-edit-relationType-name').val(relationType.name);
            $('#input-edit-relationType-id').val(relationType.id);
            $('#edit-relationType-search-result').html('');
            $('#span-edit-already-exists').addClass('d-none');
            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#relationType_name').val('');
            $('#add-relationType-search-result').html('');
            $('#span-already-exists').addClass('d-none');
            $('#addModal').modal();
        }

        const onAddRelationTypeNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-relationType-save').prop('disabled', true);

            let spinnerCheckRelationType = $('#spinner-check-relationType');
            spinnerCheckRelationType.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-relationType-search-result').html('');
                spinnerCheckRelationType.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: relationTypeNameCheckerUrl,
                    spinnerId: 'spinner-check-relationType',
                    actionButtonId: 'button-relationType-save',
                    spanId: 'span-already-exists',
                    searchResultItemId: 'add-relationType-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        relationType_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateRelationTypeNameInputChanged = (event) => {
            $('#button-relationType-update').prop('disabled', true);
            $('#edit-spinner-check-relationType').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: relationTypeNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-relationType',
                    actionButtonId: 'button-relationType-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-relationType-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        relationType_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-relationType-id').val()
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

                    if (response.similarRecords && checkerConfig.data.relationType_name.length >= 3) {
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
            $('#table-relationTypes').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection