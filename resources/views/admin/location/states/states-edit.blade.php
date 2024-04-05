@extends('layouts/contentLayoutMaster')

@section('title', 'EDIT STATE')

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
            width: 92%;
            z-index: 1;
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

    <section class="add-states">
        <div class="card">
            <form id="form-states-store" method="POST" action="{{ route('states.update', $state->id)}}"
                  class="needs-validation"
                  novalidate>
                <input type="hidden" name="_method" value="PUT" />
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Countries -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_country">Country</label>
                                <select class="form-control select2 @error('countryId') is-invalid @enderror"
                                        id="data_country" name="countryId"
                                        oninput="countryChangedHandler(event)" data-submitButtonId="button-save"
                                        required>
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true, 'selectedValue' => $state->country_id ? $state->country_id : null ])
                                </select>
                                @error('countryId')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_country-error" class="invalid-feedback d-none" role="alert">
                                        </span>
                            </div>
                        </div>

                        <!-- State Name -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_name">State Name</label>
                                <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Name"
                                        value="{{ $state->name }}"
                                        name="name"
                                        id="data_name"
                                        oninput="inputChangeHandler(event)"
                                        required
                                        data-column="name"
                                        data-table="states"
                                        data-includeColumn="country_id"
                                        data-includeSourceId="data_country"
                                        data-submitButtonId="button-save"
                                        data-dependentInputId="data-country"
                                        data-excludeOwnId="true"
                                        data-recordId="{{ $state->id }}"
                                />
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_contains($message, 'taken') ? 'State name is already exists!' : $message   }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_name-error" class="error mt-1 d-none" role="alert">
                                                State name is already exists!
                                </span>
                                <div id="search-result-data_name"
                                     class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                     aria-labelledby="dropdownMenu2">
                                </div>
                                <span id="data_name-error" class="invalid-feedback d-none" role="alert">
                                        </span>
                            </div>
                        </div>

                        <!-- Latitude -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_latitude">Latitude(optional)</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Latitude"
                                        value="{{ $state->latitude }}"
                                        name="latitude"
                                        id="data_latitude"
                                />
                                @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Latitude -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_longitude">Longitude(optional)</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Longitude"
                                        value="{{ $state->longitude }}"
                                        name="longitude"
                                        id="data_longitude"
                                />
                                @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button id="button-save" type="submit" class="btn thm-btn mb-1 mb-sm-0 mr-sm-1"
                            >SAVE CHANGES
                            </button>
                            <a id="btn-reset" href="{{route('states.index')}}" class="btn btn-outline-secondary">
                                CANCEL
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

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
        let currentTimeout = null;

        $(document).ready(function () {
            $('select').select2({
                placeholder: 'Select',
                allowClear: true
            });

            if ($('#data_country').val()) {
                console.log($('#data_country').val());
                $('#data_country').trigger('change');
            }
        });

        const clearSelectFields = () => {
            $('#data_country').val(null).trigger('change');
        }

        const countryChangedHandler = (event) => {
            console.log('changed');
           $('#data_name').trigger('input');
        }

        const resetCountryDependentInputs = () => {
            $('#data_name').val('');
            $('#button-save').prop('disabled', true);
            $('#span-data_name-error').addClass('d-none');
            $('#data_name').removeClass('is-invalid');
            $('#span-data_country-error').addClass('d-none');
            $('#data_country').removeClass('is-invalid');
            $('#search-result-data_name').html('');
        }

        //#region Validation & Autocomplete
        const inputChangeHandler = (event) => {

            if (currentTimeout) {
                clearTimeout(currentTimeout);
            }

            const inputElement = $(event.target);
            if (inputElement.attr('data-submitButtonId')) {
                $('#' + inputElement.attr('data-submitButtonId')).prop('disabled', true);
            }
            currentTimeout = setTimeout(() => {
                const requestConfig = {
                    url: '{{ route('states.search') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        column: inputElement.attr('data-column'),
                        table: inputElement.attr('data-table'),
                        elementId: inputElement.attr('id'),
                        submitButtonId: inputElement.attr('data-submitButtonId'),
                        value: inputElement.val(),
                        includeColumn: inputElement.attr('data-includeColumn'),
                        includeValue: $('#' + inputElement.attr('data-includeSourceId')).val(),
                        excludeOwnId: inputElement.attr('data-excludeOwnId'),
                        recordId: inputElement.attr('data-recordId'),
                        similarRecordsLimit: 3
                    },
                    success: onSearchResponseSuccess,
                    error: onSearchResponseErrors
                }

                sendRequest(requestConfig);
            }, 500);

        }

        const onSearchResponseSuccess = response => {
            const errorSpan = $('#span-' + response.elementId + '-error');
            const inputElement = $('#' + response.elementId);
            const inputLabel = $("label[for='" + response.elementId + "']").text();
            const searchResultElement = $('#search-result-' + response.elementId);
            const submitButton = response.submitButtonId ? $('#' + response.submitButtonId) : null;

            //reset errors
            errorSpan.addClass('d-none');
            inputElement.removeClass('is-invalid');

            if (response.includeColumnRequiredError) {
                const dependentElement = $('#' + inputElement.attr('data-includeSourceId'));
                const includeInputLabel = $("label[for='" + dependentElement.attr('id') + "']").text();
                const dependentErrorSpan = $('#span-' + dependentElement.attr('id') + '-error');
                dependentElement.addClass('is-invalid');
                dependentErrorSpan.text(`${includeInputLabel} is required!`);
                dependentErrorSpan.removeClass('d-none')
                return;
            }

            if (response.exists === false) {
                if (submitButton) {
                    submitButton.prop('disabled', false);
                }
            } else if (response.exists === true) {
                inputElement.addClass('is-invalid');
                errorSpan.text(`${inputLabel} is already exists!`)
                errorSpan.removeClass('d-none')
                if (submitButton) {
                    submitButton.prop('disabled', true);
                }
            } else {
                inputElement.addClass('is-invalid');
                errorSpan.text(`${inputLabel} is required!`);
                errorSpan.removeClass('d-none')

                if (submitButton) {
                    submitButton.prop('disabled', true);
                }
            }

            if (response.similarRecords) {
                searchResultElement.html(response.similarRecords);
            } else {
                searchResultElement.html('');
            }
        };

        const onSearchResponseErrors = response => {
            console.log('onSearchResponseErrors', response);
        };

        //#endregion

        const sendRequest = (requestConfig) => {
            $.ajax({
                url: requestConfig.url,
                type: requestConfig.method ? requestConfig.method : 'POST',
                data: requestConfig.data,
                success: requestConfig.success,
                error: requestConfig.error
            });
        }


    </script>
@endsection