@extends('layouts/contentLayoutMaster')

@section('title', 'FOUNDATIONS')

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

    <section id="foundations">
        <a onclick="showAddModal()" class="btn thm-btn mb-1">ADD</a>

        <!-- Foundation List -->
        <table id="table-foundations" class="table">
            <thead class="thead-light">
            <tr>
                <th>FOUNDATION NAME</th>
                <th>NO. OF RECORDS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($foundations as $foundation)
                <tr>
                    <td>{{ $foundation->name }}</td>
                    <td>-</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $foundation->id }}, name: '{{ $foundation->name }}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $foundation->id}} , name: '{{$foundation->name}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Foundation List END -->

        <!-- Add Modal -->
        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD FOUNDATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add Foundation -->
                            <section id="add-foundation">

                                <div class="row">
                                    <!-- Foundation name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="foundation_name">Foundation Name</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('foundation_name') is-invalid @enderror"
                                                        placeholder="Foundation Name"
                                                        value="{{old('foundation_name')}}"
                                                        name="foundation_name"
                                                        id="foundation_name"
                                                        oninput="onAddFoundationNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="spinner-check-foundation"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-already-exists" class="error mt-1 d-none" role="alert">
                                                Foundation name already exists!
                                            </span>
                                            <div id="add-foundation-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-foundation-search-result">
                                            </div>
                                            <span id="alert-foundation-name-exists" class="invalid-feedback"
                                                  role="alert">
                                                <strong>test</strong>
                                            </span>
                                            @error('foundation_name')
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

                        <input type="hidden" name="id" id="input-save-foundation-id"/>
                        <div class="modal-footer">
                            <button id="button-foundation-save" type="submit" class="btn thm-btn" disabled>SAVE
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
                        <h4 class="modal-title">DELETE FOUNDATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                                         id="deletingFoundationName"></span>?
                    </div>
                    <form method="post" id="form-delete-foundation">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-foundation-id"/>
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
                        <h4 class="modal-title">EDIT FOUNDATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-foundation" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-foundation-id"/>
                        <div class="modal-body">
                            <!-- Edit Foundation -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- Foundation name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-foundation-name">Foundation Name</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-foundation-name') is-invalid @enderror"
                                                        placeholder="Foundation Name"
                                                        value=""
                                                        name="input-edit-foundation-name"
                                                        id="input-edit-foundation-name"
                                                        oninput="onUpdateFoundationNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-foundation"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                Foundation name is already exists!
                                            </span>
                                            <div id="edit-foundation-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="edit-foundation-search-result">
                                            </div>
                                            @error('input-edit-foundation-name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Edit Foundations END -->
                        </div>

                        <div class="modal-footer">
                            <button id="button-foundation-update" type="submit" class="btn thm-btn" disabled>SAVE CHANGES
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
        const foundationNameCheckerUrl = "{{ route('foundations.search') }}",
            token = "{{ csrf_token() }}",
            foundationsDeleteUrl = "{{route('foundations.destroy','')}}",
            foundationsUpdateUrl = "{{route('foundations.update','')}}";

        const showDeleteModal = (foundation) => {
            let deleteForm = document.querySelector('#form-delete-foundation');
            deleteForm.action = `${foundationsDeleteUrl}/${foundation.id}`;
            $('#deletingFoundationName').text(foundation.name);
            $('#input-delete-foundation-id').val(foundation.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (foundation) => {
            let updateForm = document.querySelector('#form-update-foundation');
            updateForm.action = `${foundationsUpdateUrl}/${foundation.id}`;
            $('#input-edit-foundation-name').val(foundation.name);
            $('#input-edit-foundation-id').val(foundation.id);
            $('#edit-foundation-search-result').html('');
            $('#span-edit-already-exists').addClass('d-none');
            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#add-foundation-search-result').html('');
            $('#span-already-exists').addClass('d-none');
            $('#foundation_name').val('');
            $('#addModal').modal();
        }

        let lastTimeOut = null;


        const onAddFoundationNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-foundation-save').prop('disabled', true);
            let spinnerCheckFoundation = $('#spinner-check-foundation');
            spinnerCheckFoundation.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-foundation-search-result').html('');
                spinnerCheckFoundation.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: foundationNameCheckerUrl,
                    spinnerId: 'spinner-check-foundation',
                    actionButtonId: 'button-foundation-save',
                    spanId: 'span-already-exists',
                    searchResultItemId: 'add-foundation-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        foundation_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateFoundationNameInputChanged = (event) => {
            $('#button-foundation-update').prop('disabled', true);
            $('#edit-spinner-check-foundation').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: foundationNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-foundation',
                    actionButtonId: 'button-foundation-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-foundation-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        foundation_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-foundation-id').val()
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

                    if (response.similarRecords && checkerConfig.data.foundation_name.length >= 3) {
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
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script>
        $(document).ready(function () {
            $('#table-foundations').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection