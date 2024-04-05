@extends('layouts/contentLayoutMaster')

@section('title', 'CITIES')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
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

        .select2-selection__clear {
            font-size: 20px;
            right: 3px;
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
    @include('shared.alert')

    <section class="cities">
        <form type="GET" action="{{route('cities.create')}}">
            @csrf
            <input type="hidden" id="refreshed" value="no">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Countries -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_country">Country</label>
                                <select class="form-control select2" id="data_country" name="data_country"
                                        oninput="countryChangedHandler(event)">
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true, 'selectedValue'=> old('data_country')])
                                </select>
                                @error('region')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <!-- States -->
                        <div class="col-md-3 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="data_state">State</label>
                                <select class="form-control select2"
                                        id="data_state"
                                        name="data_state"
                                        disabled
                                        oninput="stateChangedHandler(event)">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button type="submit" id="button-add" class="btn thm-btn  mb-1" disabled>ADD</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <table id="table-cities" class="table">
            <thead class="thead-light">
            <tr>
                <th>NAME</th>
                <th>LATITUDE</th>
                <th>LONGITUDE</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody id="tbody-cities">
            </tbody>
        </table>

        <!-- Modals -->
        <!-- Delete Modal -->
        <div id="deleteModal" class="modal fade modal-danger">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DELETE STATE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                              id="deletingDataName"></span>?
                    </div>
                    <form method="post" id="form-delete">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-data_id"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">DELETE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Modal END -->
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
@endsection

@section('page-script')
    <script>
        let dataTable = null;

        onload=function(){
            console.log(':D');
            var e=document.getElementById("refreshed");
            if(e.value=="no")e.value="yes";
            else{e.value="no";location.reload();}
        }


        $(document).ready(function () {
            $('select').select2({
                placeholder: 'Select',
                allowClear: true
            });

            if ($('#data_country').val()) {
                $('#data_country').trigger('change');
                const selectedStateId = {{ old('data_state') != null ?  old('data_state') : 'null' }};
                console.log('StateId: ' + selectedStateId);

                const requestConfig = {
                    url: "{{ route('states.getStateOptions') }}",
                    data: {
                        countryId: $('#data_country').val(),
                        stateId: selectedStateId,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: onStateOptionsSuccess,
                    error: onStateOptionsError
                };
                sendRequest(requestConfig);

                if(selectedStateId){
                    const requestConfig = {
                        url: "{{ route('cities.getCityTableRows') }}",
                        data: {
                            stateId: selectedStateId,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: onCityRowsFetchSuccess,
                        error: onCityRowsFetchSuccess
                    };

                    sendRequest(requestConfig);
                }
            }
            dataTable = $('#table-cities').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });

        const showDeleteModal = (data) => {
            let deleteForm = document.querySelector('#form-delete');
            const deleteUrl = "{{ route('cities.destroy', '')}}";
            deleteForm.action = `${deleteUrl}/${data.id}`;
            $('#deletingDataName').text(data.name);
            $('#input-delete-data_id').val(data.id);
            $('#deleteModal').modal();
        };

        //#region Fetching States
        const countryChangedHandler = (event) => {
            const countryId = event.target.value;
            if (!countryId.length) {
                // $("#tbody-states").html('');
                $('#button-add').prop('disabled', true);
                dataTable.clear().draw();
                $('#data_state').html('').trigger('change');
                $('#data_state').attr('disabled', true);
                return;
            }

            const requestConfig = {
                url: "{{ route('states.getStateOptions') }}",
                data: {
                    countryId: countryId,
                    "_token": "{{ csrf_token() }}"
                },
                success: onStateOptionsSuccess,
                error: onStateOptionsError
            };

            sendRequest(requestConfig);
        };

        const onStateOptionsSuccess = (response) => {
            dataTable.clear().draw();

            //#region Clear Table
            //  if (dataTable) {
            //     dataTable.destroy();
            // }
            // $("#tbody-cities").html('');
            // dataTable = $('#table-cities').DataTable({
            //     "drawCallback": function (settings) {
            //         feather.replace();
            //     }
            // });
            //#endregion

            $('#data_state').html(response.stateOptions).trigger('change');
            if (response.stateOptions) {
                $('#data_state').prop('disabled', false);
            }
        };
        const onStateOptionsError = (response) => {
            console.log('error', response);
        };
        //#endregion

        const stateChangedHandler = event => {
            if (!event.target.value) {
                dataTable.clear().draw();
                $('#button-add').prop('disabled', true);
                return;
            }

            const requestConfig = {
                url: "{{ route('cities.getCityTableRows') }}",
                data: {
                    stateId: event.target.value,
                    "_token": "{{ csrf_token() }}"
                },
                success: onCityRowsFetchSuccess,
                error: onCityRowsFetchSuccess
            };

            sendRequest(requestConfig);
        };

        const onCityRowsFetchSuccess = (response) => {
            if (dataTable) {
                dataTable.destroy();
            }
            $("#tbody-cities").html(response.cityTableRows);
            dataTable = $('#table-cities').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });

            if(response.cityTableRows){
                $('#button-add').prop('disabled', false);
            }
        };

        const onCityRowsFetchError = (response) => {
            console.log('onCityRowsFetchError', response);
        };

        const sendRequest = (requestConfig) => {
            $.ajax({
                url: requestConfig.url,
                data: requestConfig.data,
                type: requestConfig.method ? requestConfig.method : 'POST',
                success: requestConfig.success,
                error: requestConfig.error
            });
        };
    </script>
@endsection