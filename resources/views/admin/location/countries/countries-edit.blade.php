@extends('layouts/contentLayoutMaster')

@section('title', 'EDIT COUNTRY')

@section('vendor-style')
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

    <section class="add-country">
        <div class="card">
            <form id="form-country-store" method="POST" action="{{ route('countries.update', $country->id) }}" class="needs-validation"
                  novalidate>
                <input type="hidden" name="_method" value="PUT" />
                <input type="hidden" name="id" id="data_id" value="{{$country->id}}" />
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Country Name -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_name">Country Name</label>
                                <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Name"
                                        value="{{old('name') ? old('name') : $country->name}}"
                                        name="name"
                                        id="data_name"
                                        oninput="onInputChanged(event)"
                                        required
                                />
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_contains($message, 'taken') ? 'Country name is already exists!' : $message   }}</strong>
                                        </span>
                                @enderror
                                <span id="data_name-error" class="invalid-feedback d-none" role="alert">
                                        </span>
                            </div>
                        </div>

                        <!-- Country Code -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_country_code">Country Code</label>
                                <input
                                        type="text"
                                        class="form-control @error('iso2') is-invalid @enderror"
                                        placeholder="Name"
                                        value="{{old('iso2') ? old('iso2') : $country->iso2}}"
                                        name="iso2"
                                        id="data_country_code"
                                        oninput="onInputChanged(event)"
                                        required
                                />
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ str_contains($message, 'taken') ? 'Country code is already exists!' : $message   }}</strong>
                                        </span>
                                @enderror
                                <span id="data_country_code-error" class="invalid-feedback d-none" role="alert">
                                        </span>
                            </div>
                        </div>

                        <!-- Native Name -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_native_name">Native Name(optional)</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Native Name"
                                        value="{{old('native') ? old('native') : $country->native}}"
                                        name="native"
                                        id="data_native_name"
                                />
                                @error('native')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Region Name -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_region">Region(optional)</label>
                                <select class="form-control select2" id="data_region" name="region">
                                    <option></option>
                                    @foreach($regions as $region)
                                        <option value="{{$region->region}}" @if( $country->region != null && $region->region == $country->region) selected @endif>{{ $region->region}}</option>
                                    @endforeach
                                </select>
                                @error('region')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Flag Emoji Name -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="data_flag_emoji">Flag Emoji(optional)</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Flag Emoji"
                                        value="{{old('emoji') ? old('emoji') : $country->emoji}}"
                                        name="emoji"
                                        id="data_flag_emoji"
                                />
                                @error('emoji')
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
                            <a id="btn-reset" href="{{route('countries.index')}}" class="btn btn-outline-secondary">
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
            $("#form-country-store").validate({
                rules: {
                    name: "required"
                },
                messages: {
                    name: "Country name is required!"
                }
            });
        });


        $('select').select2({
            placeholder: 'Select',
            allowClear: true
        });

        const clearForm = () => {
            $('#data_region').val(null).trigger('change');
        }

        const onInputChanged = (event) => {
            if (currentTimeout) {
                clearTimeout(currentTimeout);
            }

            $('#form-country-store').valid();
            if (!event.target.value.length) {
                return;
            }

            currentTimeout = setTimeout(() => {

                const validationConfig = {
                    url: " {{ route('countries.search') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        fieldName: 'name',
                        value: event.target.value,
                        senderId: event.target.id,
                        recordId: $('#data_id').val()
                    },
                    disabledElementId: 'button-save'
                }

                validateInput(validationConfig);
            }, 500);
        }

        const validateInput = validationConfig => {
            $.ajax({
                url: validationConfig.url,
                type: validationConfig.method ? validationConfig.method : 'POST',
                data: validationConfig.data,
                success: onValidationSuccessResponse,
                error: onValidationErrorResponse
            });
        }

        const onValidationSuccessResponse = (response) => {
            if (response.status == 'exists') {
                console.log(response);
                $('#' + response.senderId).addClass('error');
                $('#' + response.senderId + '-error').removeClass('d-none').addClass('d-block');
                $('#' + response.senderId + '-error').text('Country name is already exists!')
            } else {
                $('#' + response.senderId + '-error').removeClass('d-block').addClass('d-none');
                if ($('#' + response.senderId).val()) {
                    $('#' + response.senderId).removeClass('error');
                }
            }
        }

        const onValidationErrorResponse = (response) => {
            console.log('Validation error:', response);
        }

    </script>
@endsection