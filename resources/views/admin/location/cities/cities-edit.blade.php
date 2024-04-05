@extends('layouts/contentLayoutMaster')

@section('title', 'EDİT CITY')

@section('vendor-style')
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
    <section class="add-cities">
        <div class="card">
            <form id="form-states-store" method="POST" action="{{ route('cities.update', $city->id) }}" class="needs-validation"
                  novalidate>
                <input type="hidden" name="_method" value="PUT" />
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Country -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_country">Country</label>
                                <select class="form-control select2 @error('data_country') is-invalid @enderror"
                                        id="data_country" name="data_country"
                                        oninput="countryChangedHandler(event)" data-submitButtonId="button-save"
                                        required>
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true, 'selectedValue' => old('data_country') ?  old('data_country') : ($city->country_id ? $city->country_id : null)])
                                </select>
                                @error('data_country')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_country-error" class="invalid-feedback d-none" role="alert">
                                        </span>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_state">State</label>
                                <select class="form-control select2 @error('data_state') is-invalid @enderror"
                                        id="data_state" name="data_state"
                                        data-submitButtonId="button-save"
                                        oninput="stateChangedHandler(event)"
                                        required
                                        disabled>
                                    <option></option>
                                </select>
                                @error('data_state')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_state-error" class="invalid-feedback d-none" role="alert">
                                        </span>
                            </div>
                        </div>

                        <!-- City Name -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_name">City Name</label>
                                <input
                                        type="text"
                                        class="form-control @error('data_name') is-invalid @enderror"
                                        placeholder="City Name"
                                        value="{{ $city->name }}"
                                        name="data_name"
                                        id="data_name"
                                        oninput="inputChangeHandler(event)"
                                        required
                                        data-column="name"
                                        data-table="cities"
                                        data-includeColumn="state_id"
                                        data-includeSourceId="data_state"
                                        data-dependentInputId="data-country"
                                        data-excludeOwnId="true"
                                        data-recordId="{{ $city->id }}"
                                />
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_contains($message, 'taken') ? 'Country name is already exists!' : $message   }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_name-error" class="error mt-1 d-none" role="alert">
                                                City name is already exists!
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
                                <label for="data_latitude">Latitude</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Latitude"
                                        value="{{ $city->latitude }}"
                                        name="data_latitude"
                                        id="data_latitude"
                                        data-dependentInputId="data_name"
                                        oninput="onFormInputChanged(event)"
                                        required
                                />
                                @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_latitude-error" class="error mt-1 d-none" role="alert">
                                    Latitude is required!
                                </span>
                            </div>
                        </div>

                        <!-- Longitude -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_longitude">Longitude</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Longitude"
                                        value="{{ $city->longitude }}"
                                        name="data_longitude"
                                        id="data_longitude"
                                        data-dependentInputId="data_name"
                                        oninput="onFormInputChanged(event)"
                                        required
                                />
                                @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <span id="span-data_longitude-error" class="error mt-1 d-none" role="alert">
                                    Longitude is required!
                                </span>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button id="button-save" type="submit" class="btn thm-btn mb-1 mb-sm-0 mr-sm-1"
                            >SAVE CHANGES
                            </button>
                            <a href="{{route('cities.index')}}" id="btn-cancel" class="btn btn-outline-secondary">
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
                const requestConfig = {
                    url: "{{ route('states.getStateOptions') }}",
                    data: {
                        countryId: $('#data_country').val(),
                        stateId: {{ $city->state_id != null ?  $city->state_id : 'null' }},
                        "_token": "{{ csrf_token() }}"
                    },
                    success: onStateOptionsSuccess,
                    error: onStateOptionsError
                };

                sendRequest(requestConfig);
            }
        });


        const onStateOptionsSuccess = (response) => {
            $('#data_state').html(response.stateOptions).trigger('change');
            if (response.stateOptions) {
                $('#data_state').prop('disabled', false);
            }
            $('#data_state').trigger('change');
        };

        const onStateOptionsError = (response) => {
            console.log('error', response);
        };

        const clearSelectFields = () => {
            $('#data_country').val(null).trigger('change');
            $('#data_state').val(null).trigger('change');
        }

        const countryChangedHandler = (event) => {
            //reset Autocomplete and Validation errors
            //resetCountryDependentInputs();

            $('#button-save').prop('disabled', true);
            $('#span-data_country-error').addClass('d-none');
            $('#data_country').removeClass('is-invalid');
            $('#span-data_state-error').addClass('d-none');
            $('#data_state').removeClass('is-invalid').attr('disabled', true).val(null).trigger('change');
            $('#search-result-data_name').html('');

            if (event.target.value) {
                const requestConfig = {
                    url: "{{ route('states.getStateOptions') }}",
                    data: {
                        countryId: event.target.value,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: onStateOptionsSuccess,
                    error: onStateOptionsError
                };

                sendRequest(requestConfig);
            }

            validateElement(event.target.id);
        }

        const resetCountryDependentInputs = () => {
            $('#data_name').val('').removeClass('is-invalid');
            ;
            $('#span-data_name-error').addClass('d-none');
            $('#button-save').prop('disabled', true);
            $('#span-data_country-error').addClass('d-none');
            $('#data_country').removeClass('is-invalid');
            $('#span-data_state-error').addClass('d-none');
            $('#data_state').removeClass('is-invalid').attr('disabled', true).val(null).trigger('change');
            $('#search-result-data_name').html('');
        }

        const resetStateDependentInputs = () => {
            $('#data_name').val('').removeClass('is-invalid');
            $('#span-data_name-error').addClass('d-none');
            $('#button-save').prop('disabled', true);
            $('#span-data_state-error').addClass('d-none');
            $('#data_state').removeClass('is-invalid');
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
                    url: '{{ route('cities.search') }}',
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

            validateFormAndSetButtonState();
        };

        const onSearchResponseErrors = response => {
            console.log('onSearchResponseErrors', response);
        };

        const validateFormAndSetButtonState = () => {
            //tüm formlar dolu mu ve error field'lar aktif değil mi

            if ($('#data_country').val() && $('#data_state').val() &&
                $('#data_name').val() && $('#data_latitude').val() &&
                $('#data_longitude').val() && $('#span-data_name-error').hasClass('d-none')) {
                $('#button-save').prop('disabled', false);
                return;
            }
            $('#button-save').prop('disabled', true);
        }
        //#endregion

        const stateChangedHandler = (event) => {
            // resetStateDependentInputs();
            validateElement(event.target.id);
            $('#data_name').trigger('input');
        }

        const onFormInputChanged = event => {
            validateElement(event.target.id);
        }

        const validateElement = elementId => {
            const validatingElement = $('#' + elementId);
            console.log('valdatingElement', elementId);

            if(validatingElement.prop('required')){
                console.log(elementId + ' required');
                const errrorField = $('#span-' + elementId + '-error');
                console.log(errrorField);
                if(!validatingElement.val()){
                    const inputLabel = $("label[for='" + elementId + "']").text();
                    errrorField.text(inputLabel + ' is required!');
                    errrorField.removeClass('d-none');
                    validatingElement.addClass('is-invalid');
                }
                else {
                    console.log('valid')
                    errrorField.addClass('d-none');
                    validatingElement.removeClass('is-invalid');
                }
            }
            else {
                console.log(elementId + ' not required');
            }

            if(validatingElement.attr('data-dependentInputId')){
                console.log('dependent element', validatingElement.attr('data-dependentInputId'))
                validateElement(validatingElement.attr('data-dependentInputId'));
            }

            validateFormAndSetButtonState();
        }

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