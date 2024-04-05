@extends('layouts/contentLayoutMaster')

@section('title', 'INDUSTRIES')

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

    <section id="industries">

        <a onclick="showAddModal()" class="btn thm-btn  mb-1">ADD</a>

        <!-- Industry List -->
        <table id="table-industries" class="table">
            <thead class="thead-light">
            <tr>
                <th>INDUSTRY NAME</th>
                <th>NO. OF RECORDS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($industries as $industry)
                <tr>
                    <td>{{ $industry->name }}</td>
                    <td>-</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $industry->id }}, name: '{{ $industry->name }}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $industry->id}} , name: '{{$industry->name}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Industry List END -->

        <!-- Add Modal -->
        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD INDUSTRY</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add Industries -->
                            <section id="add-industries">

                                <div class="row">
                                    <!-- Industry name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="industry_name">Industry Name</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('industry_name') is-invalid @enderror"
                                                        placeholder="Industry Name"
                                                        value="{{old('industry_name')}}"
                                                        name="industry_name"
                                                        id="industry_name"
                                                        oninput="onAddIndustryNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="spinner-check-industry"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-add-already-exists" class="error mt-1 d-none" role="alert">
                                                Industry name already exists!
                                            </span>
                                            <div id="add-industry-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="dropdownMenu2">
                                            </div>
                                            @error('industry_name')
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

                        <input type="hidden" name="id" id="input-save-industry-id"/>
                        <div class="modal-footer">

                            <button id="button-industry-save" type="submit" class="btn thm-btn" disabled>SAVE
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
                        <h4 class="modal-title">DELETE INDUSTRY</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                              id="deletingIndustryName"></span>?
                    </div>
                    <form method="post" id="form-delete-industry">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-industry-id"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">REMOVE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete END -->

        <!-- Edit Modal -->
        <div id="editModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">EDIT INDUSTRY</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-industry" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-industry-id"/>
                        <div class="modal-body">
                            <!-- Edit Industries -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- Industry name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-industry-name">Industry Name</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-industry-name') is-invalid @enderror"
                                                        placeholder="Industry Name"
                                                        value=""
                                                        name="input-edit-industry-name"
                                                        id="input-edit-industry-name"
                                                        oninput="onUpdateIndustryNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-industry"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                Industry name is already exists!
                                            </span>
                                            <span id="alert-edit-already-exists" class="invalid-feedback" role="alert">
                                                <strong>test</strong>
                                            </span>
                                            @error('input-edit-industry-name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <div id="edit-industry-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="dropdownMenu2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Edit Industries END -->
                        </div>

                        <div class="modal-footer">
                            <button id="button-industry-update" type="submit" class="btn thm-btn">SAVE
                                CHANGES
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

        const industryNameCheckerUrl = "{{ route('industries.search') }}",
            token = "{{ csrf_token() }}",
            industryDeleteUrl = "{{route('industries.destroy','')}}",
            industryUpdateUrl = "{{route('industries.update','')}}";

        const showDeleteModal = (industry) => {
            let deleteForm = document.querySelector('#form-delete-industry');
            deleteForm.action = `${industryDeleteUrl}/${industry.id}`;
            $('#deletingIndustryName').text(industry.name);
            $('#input-delete-industry-id').val(industry.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (industry) => {
            let updateForm = document.querySelector('#form-update-industry');
            updateForm.action = `${industryUpdateUrl}/${industry.id}`;
            $('#input-edit-industry-name').val(industry.name);
            $('#input-edit-industry-id').val(industry.id);
            $('#span-edit-already-exists').addClass('d-none');
            $('#edit-industry-search-result').html('');
            $('#button-industry-update').prop('disabled', false);
            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#addModal').modal();
            $('#industry_name').val('');
            $('#add-industry-search-result').html('');
            $('#span-add-already-exists').addClass('d-none');
            $('#addModal').modal();
        }

        const onAddIndustryNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-industry-save').prop('disabled', true);
            let spinnerCheckIndustry = $('#spinner-check-industry');
            spinnerCheckIndustry.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-industry-search-result').html('');
                spinnerCheckIndustry.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: industryNameCheckerUrl,
                    industryName: 'name',
                    spinnerId: 'spinner-check-industry',
                    actionButtonId: 'button-industry-save',
                    spanId: 'span-add-already-exists',
                    searchResultItemId: 'add-industry-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        industry_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateIndustryNameInputChanged = (event) => {
            $('#button-industry-update').prop('disabled', true);
            $('#edit-spinner-check-industry').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: industryNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-industry',
                    actionButtonId: 'button-industry-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-industry-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        industry_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-industry-id').val()
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

                    if (response.similarRecords && checkerConfig.data.industry_name.length >= 3) {
                        searchResultItem.html(response.similarRecords);
                    } else {
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
            $('#table-industries').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection