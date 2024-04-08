@extends('layouts/contentLayoutMaster')

@section('title', 'DASHBOARD')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="https://pn-ciamis.go.id/asset/DataTables/extensions/Select/css/select.dataTables.css">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">

{{--    <link rel="stylesheet" href="{{asset('/backend/css/constants.css')}}">--}}
{{--    <link rel="stylesheet" href="{{asset('/backend/css/sync.css')}}">--}}
    <style>
        .select2-selection__clear {
            font-size: 20px;
            right: 3px;
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
    @include('shared.alert')
    <!-- Philanthropists Search -->
    <section class="philanthropist-search">
        <div class="row">
            <!-- Name -->
            <div class="col-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                            type="text"
                            class="form-control"
                            placeholder="Name"
                            value=""
                            name="first_name"
                            id="name"
                            onkeyup="searchInputKeydownHandler(event)"
                            required
                    />
                </div>
            </div>
            <!-- Birth Year -->
            <div class="col-3 col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="birth-year">Birth Year</label>
                    <select id="birth-year" class="form-control select2 year-select">
                        <option></option>
                        @for ($year = 1700; $year <= date('Y'); $year++)
                            <option value="{{ $year }}">{{$year}}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Death Year -->
            <div class="col-3 col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="death-year">Death Year</label>
                    <select id="death-year" class="form-control select2 year-select">
                        <option></option>
                        @for ($year = 1700; $year <= date('Y'); $year++)
                            <option value="{{ $year }}">{{$year}}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-4 col-lg-3">
                <label>Actions</label>
                <div class="form-group">
                    <div class="d-flex">
                        <button id="btn-search" class="btn thm-btn form-control"
                                style="margin-bottom: 0.5rem; margin-right: 0.5rem" onclick="searchPhilanthropists();">
                            SEARCH
                        </button>
                        <a href="{{route('philanthropists.add')}}" class="btn thm-btn form-control" style="margin-bottom: 0.5rem;">ADD</a>
                    </div>
                    <a class="base-color" onclick="toggleAdvancedPanel()">Advanced Search</a>
                </div>
            </div>
        </div>

        <div id="panel-advanced-search" class="row" style="display: none">
            <div class="col-12"></div>
            <!-- Statuses -->
            <div class="col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control select2">
                        <option></option>
                        @foreach($statuses as $status)
                            <option value="{{$status}}">{{ucfirst($status)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Industries -->
            <div class="col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <select id="industry" class="form-control select2">
                        <option></option>
                        @foreach($industries as $industry)
                            <option value="{{$industry->id}}">{{$industry->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Business Name -->
            <div class="col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="business-name">Business Name</label>
                    <input type="text"
                           class="form-control"
                           placeholder="Business Name"
                           value=""
                           name="business-name"
                           id="business-name"
                           onkeydown="searchInputKeydownHandler(event)"
                           required
                    />
                </div>
            </div>

            <!-- Institution Name -->
            <div class="col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="institution-name">Institution Name</label>
                    <input type="text"
                           class="form-control"
                           placeholder="Institution Name"
                           value=""
                           name="institution-name"
                           id="institution-name"
                           onkeydown="searchInputKeydownHandler(event)"
                           required
                    />
                </div>
            </div>

            <!-- Foundation Name -->
            <div class="col-md-2 col-lg-2">
                <div class="form-group">
                    <label for="foundation-name">Foundation Name</label>
                    <input type="text"
                           class="form-control"
                           placeholder="Foundation Name"
                           value=""
                           name="foundation-name"
                           id="foundation-name"
                           onkeypress="searchInputKeydownHandler(event)"
                           required
                    />
                </div>
            </div>
        </div>
    </section>
    <!-- Philanthropists Search END -->
    <section class="philanthropist-list mt-1">
        <h2>Results <span id="spinner-search" class="spinner-border text-primary d-none"></span></h2>
        <button id="btn-delete" class="btn btn-danger d-none" onclick="deleteSelectedRecords()">DELETE</button>
        <table id="table-philanthropists" class="table">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>BIRTH</th>
                    <th>DEATH</th>
                    <th>BUSINESS</th>
                    <th>INSTITUTION</th>
                    <th>FOUNDATION</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody id="tbody-philanthropists">
                @include('admin.library.philanthropists.philanthropists-search-result', ['philanthropists' => $philanthropists])
            </tbody>
        </table>

        <!-- Delete Modal -->
        <div id="deleteModal" class="modal fade modal-danger">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DELETE PHILANTHROPISTS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                              id="deletingRecordName"></span>?
                    </div>
                    <form method="post" id="form-delete-record">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="deletingRecordId"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">REMOVE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete END -->
    </section>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

@endsection

@section('page-script')
    <script>

        let dataTable = null;
        const deleteUrl = "{{route('philanthropists.destroy','')}}";

        const dataTableInitializer = {
            "ordering": true,
            "order": [7],
            "drawCallback": function (settings) {
                feather.replace();
            },
            // Minimum ammount of rows to display
            "lengthMenu": [100, 500],
            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                    'selectRow': true
                    }
                }
            ],
            'select': {
                'style': 'multi'
            },
        };
        $(document).ready(function () {
            dataTable = $('#table-philanthropists').DataTable(dataTableInitializer);

            // When someone presses a checkbox, show delete button
            $('#table-philanthropists').on('change', 'input[type="checkbox"]', function () {
                if ($('#table-philanthropists input[type="checkbox"]:checked').length > 0) {
                    $('#btn-delete').removeClass('d-none');
                } else {
                    $('#btn-delete').addClass('d-none');
                }
            });

            // When someone presses the delete button, delete the selected records
            $('#btn-delete').on('click', function () {
                deleteSelectedRecords();
            });

            // When someone presses the delete button, delete the selected records
            const deleteSelectedRecords = () => {
                let selectedRecords = [];
                $('#tbody-philanthropists input[type="checkbox"]:checked').each(function () {
                    // Get closest hidden input
                    selectedRecords.push($(this).closest('tr').find('input[type="hidden"]').val());
                });

                if (selectedRecords.length > 0) {
                    $('#btn-delete').prop('disabled', true);
                    $('#btn-delete').text('Deleting...')

                    $.ajax({
                        url: '{{ route('philanthropists.destroyAll') }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            philanthropistIds: selectedRecords
                        },
                        method: 'POST',
                        success: function (response) {
                            console.log('success', response);

                            $('#btn-delete').text('SUCCESS');
                            window.location.reload();
                            $('#btn-delete').prop('disabled', false);
                        },
                        error: function (response) {
                            console.log('error', response);
                            $('#btn-delete').prop('disabled', false);
                        },
                    });
                }
            }
        });

        $('.select2').select2({
            allowClear: true,
            placeholder: 'Select',
        });

        $('.year-select').on('select2:open', function (e) {
            const selectItem = e.currentTarget;
            const liItemId = `select2-${selectItem.id}-results`;
            if (selectItem.selectedIndex === 0) {
                setTimeout(() => {
                    const liItem = document.querySelector('#' + liItemId);
                    liItem.scrollTo(0, 3200);
                }, 10);
            }
        });



        const searchInputKeydownHandler = (event) => {
            if(event.keyCode == 13){
                searchPhilanthropists();
            }
        }

        const toggleAdvancedPanel = () => {
            $('#panel-advanced-search').slideToggle(55);
        }

        const searchPhilanthropists = () => {
            $('#spinner-search').removeClass('d-none');
            $('#btn-search').prop('disabled', true);
            $.ajax({
                url: '{{route('philanthropists.search')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    name: $('#name').val(),
                    birth_year: $('#birth-year').val(),
                    death_year: $('#death-year').val(),
                    status: $('#status').val(),
                    industry: $('#industry').val(),
                    business_name: $('#business-name').val(),
                    institution_name: $('#institution-name').val(),
                    foundation_name: $('#foundation-name').val(),
                },
                success: function (response) {
                    if(dataTable){
                        dataTable.destroy();
                    }
                    $('#tbody-philanthropists').html('');
                    $('#tbody-philanthropists').html(response);
                    dataTable = $('#table-philanthropists').DataTable(dataTableInitializer);
                    $('#spinner-search').addClass('d-none');
                    $('#btn-search').prop('disabled', false);

                },
                error: function (response) {
                    console.log('error', response);
                    $('#spinner-search').addClass('d-none');
                    $('#btn-search').prop('disabled', false);
                },
            });
        }

        //#region Modals
        const showDeleteModal = (philanthropist) => {
            let deleteForm = document.querySelector('#form-delete-record');
            deleteForm.action = `${deleteUrl}/${philanthropist.id}`;
            $('#deletingRecordName').text(philanthropist.name);
            $('#deletingRecordId').val(philanthropist.id);
            $('#deleteModal').modal();
        }
        //#endregion

    </script>
@endsection