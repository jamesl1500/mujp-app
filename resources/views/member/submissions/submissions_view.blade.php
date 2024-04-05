@extends('layouts/contentLayoutMaster')

@section('title', 'EDIT PHILANTHROPIST')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('fonts/font-awesome/css/font-awesome.min.css'))}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/jstree.min.css'))}}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">

    <style>
        .flex-spinner {
            margin-top: 3px;
            margin-left: 8px;
        }

        .select2-selection__clear {
            font-size: 20px;
            right: 3px;
            color: #dc3545;
        }

        .inline-spacing {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: center;
        }

        .dz-button {
            font-size: 1.5rem !important;
        }

        .dz-message {
            font-size: 1.5rem !important;
        }

        .dropzone .dz-message::before {
            width: 60px !important;
            height: 60px !important;
        }

        .file-redirect {
            height: 3.75rem !important;
        }

        .file-tag {
            margin-left: 2px;
        }

        .select2-selection__rendered{
            background-color: #efefef !important;
            opacity: 1 !important;
            color: #6e6b7b !important;
        }
    </style>
@endsection

@section('content')
    @include('shared.alert')

    <form id="form-save" method="POST" action="{{route('philanthropists.update', $philanthropist->id)}}"
          enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="put"/>
        <input type="hidden" name="philanthropist_id" id="philanthropist_id" value="{{$philanthropist->id}}">
        <input type="hidden" name="deleted_related_peoples" id="deleted_related_peoples">
        <input type="hidden" name="deleted_associated_peoples" id="deleted_associated_peoples">
        <input type="hidden" name="deleted_institutions" id="deleted_institutions">

    {{--        <div id="dump-test">{{}}</div>--}}
    <!-- Personal Information -->
        <section id="personal-information">
            <div class="card">
                <h2 class="card-header">PERSONAL INFORMATION</h2>
                <div class="card-body">
                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="First Name"
                                        value="{{$philanthropist->firstname}}"
                                        name="firstname"
                                        id="firstname"
                                        disabled
                                />
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Last Name"
                                        value="{{$philanthropist->lastname}}"
                                        name="lastname"
                                        id="lastname"
                                        disabled
                                />
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="d-block mb-1">Gender</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="male" name="gender"
                                           value="male"
                                           class="custom-control-input"
                                            {{ $philanthropist->gender == 'male' ? 'checked' : '' }}
                                           disabled/>
                                    <label class="custom-control-label"
                                           for="male">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="female" name="gender"
                                           value="female"
                                           class="custom-control-input"
                                            {{ $philanthropist->gender == 'female' ? 'checked' : '' }} disabled/>
                                    <label class="custom-control-label"
                                           for="female">Female</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Date Inputs -->
                    <div class="row">

                        <!-- Month of Birth -->
                        <div class="col-md-4 col-lg-2 col-4 date-input-small">
                            <div class="form-group">
                                <label for="month_of_birth">Month of Birth</label>
                                <select class="form-control select2" id="month_of_birth" name="month_of_birth" disabled>
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}" @isset($philanthropist->month_of_birth) {{ $month == $philanthropist->month_of_birth ? 'selected' : '' }} @endisset>{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-4 col-lg-2 col-4 date-input-small">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <select class="form-control select2" id="date_of_birth" name="date_of_birth" disabled>
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}" @isset($philanthropist->date_of_birth) {{ $date == $philanthropist->date_of_birth ? 'selected' : '' }} @endisset>{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Year of Birth -->
                        <div class="col-md-4 col-lg-2 col-4 date-input-small">
                            <div class="form-group">
                                <label for="year_of_birth">Year of Birth</label>
                                <select class="form-control select2 year-select" id="year_of_birth"
                                        name="year_of_birth" disabled>
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}" @isset($philanthropist->year_of_birth) {{ $year == $philanthropist->year_of_birth ? 'selected' : '' }} @endisset>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Jewish Month of Birth -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_month_of_birth">Jewish Month of Birth</label>
                                <select class="form-control select2" id="jewish_month_of_birth"
                                        name="jewish_month_of_birth" disabled>
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}" @isset($philanthropist->jewish_month_of_birth) {{ $month == $philanthropist->jewish_month_of_birth ? 'selected' : '' }} @endisset>{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Date of Birth -->
                        <div class="col-4 col-md-4 col-lg-2  date-input-small">
                            <div class="form-group">
                                <label for="jewish_date_of_birth">Jewish Date of Birth</label>
                                <select class="form-control select2" id="jewish_date_of_birth"
                                        name="jewish_date_of_birth" disabled>
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}" @isset($philanthropist->jewish_date_of_birth) {{ $date == $philanthropist->jewish_date_of_birth ? 'selected' : '' }} @endisset>{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Year of Birth -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_year_of_birth">Jewish Year of Birth</label>
                                <select class="form-control select2 year-select" id="jewish_year_of_birth"
                                        name="jewish_year_of_birth" disabled>
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}" @isset($philanthropist->jewish_year_of_birth) {{ $year == $philanthropist->jewish_year_of_birth ? 'selected' : '' }} @endisset>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <!-- Month of Death -->
                        <div class="col-4  col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="month_of_death">Month of Death</label>
                                <select class="form-control select2" id="month_of_death" name="month_of_death" disabled>
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}" @isset($philanthropist->month_of_death) {{ $month == $philanthropist->month_of_death ? 'selected' : '' }} @endisset>{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Date of Death -->
                        <div class="col-4 col-md-4 col-lg-2  date-input-small">
                            <div class="form-group">
                                <label for="date_of_death">Date of Death</label>
                                <select class="form-control select2" id="date_of_death" name="date_of_death" disabled>
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}" @isset($philanthropist->date_of_death) {{ $date == $philanthropist->date_of_death ? 'selected' : '' }} @endisset>{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Year of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="year_of_death">Year of Death</label>
                                <select class="form-control select2 year-select" id="year_of_death"
                                        name="year_of_death" disabled>
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}" @isset($philanthropist->year_of_death) {{ $year == $philanthropist->year_of_death ? 'selected' : '' }} @endisset>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <!-- Jewish Month of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_month_of_death">Jewish Month of Death</label>
                                <select class="form-control select2" id="jewish_month_of_death"
                                        name="jewish_month_of_death" disabled>
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}" @isset($philanthropist->jewish_month_of_death) {{ $month == $philanthropist->jewish_month_of_death ? 'selected' : '' }} @endisset>{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Date of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_date_of_death">Jewish Date of Death</label>
                                <select class="form-control select2" id="jewish_date_of_death"
                                        name="jewish_date_of_death" disabled>
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}" @isset($philanthropist->jewish_date_of_death) {{ $date == $philanthropist->jewish_date_of_death ? 'selected' : '' }} @endisset>{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Year of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_year_of_death">Jewish Year of Death</label>
                                <select class="form-control select2 year-select" id="jewish_year_of_death"
                                        name="jewish_year_of_death" disabled>
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}" @isset($philanthropist->jewish_year_of_death) {{ $year == $philanthropist->jewish_year_of_death ? 'selected' : '' }} @endisset>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Date Inputs END -->
                    <!-- Birth Location Inputs-->
                    <div class="row">
                        <!-- Country of Birth -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="country-of-birth">Country of Birth</label>
                                <select class="form-control select2" name="country-of-birth" id="country-of-birth" disabled>
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true, 'selectedValue' => $selectedCountryId])
                                </select>
                            </div>
                        </div>

                        <!-- State of Birth -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="state-of-birth">State of Birth</label>
                                <select class="form-control select2" name="state-of-birth" id="state-of-birth" disabled>
                                    @include('shared.option-list', ['options' => $states, 'addEmptyOption' => true, 'selectedValue' => $selectedStateId])
                                </select>
                            </div>
                        </div>

                        <!-- City of Birth -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="city_of_birth">City of Birth</label>
                                <select class="form-control select2" id="city_of_birth" name="city_of_birth" disabled>
                                    @include('shared.option-list', ['options' => $cities, 'addEmptyOption' => true, 'selectedValue' => $philanthropist->city_of_birth])
                                </select>
                            </div>
                        </div>

                        <!-- Other Address Field -->
                        <div class="col-12 col-lg-3 col-6">
                            <div class="form-group">
                                <label for="city_of_birth_other">Other</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Other Location"
                                        value="{{ $philanthropist->city_of_birth_other }}"
                                        name="city_of_birth_other"
                                        id="city_of_birth_other"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Most Lived In Location Inputs-->
                    <div class="row">
                        <!-- Country of Most Lived in -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="country_of_most_lived_in">Country of Most Lived in</label>
                                <select class="form-control select2" name="country_of_most_lived_in"
                                        id="country_of_most_lived_in" disabled>
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true, 'selectedValue' => $selectedMostLivedInCountryId])
                                </select>
                            </div>
                        </div>

                        <!-- State of Most Lived in -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="state_of_most_lived_in">State of Most Lived in</label>
                                <select class="form-control select2" name="state_of_most_lived_in"
                                        id="state_of_most_lived_in" disabled>
                                    @include('shared.option-list', ['options' => $mostLivedInStates, 'addEmptyOption' => true, 'selectedValue' => $selectedMostLivedInStateId])
                                </select>
                            </div>
                        </div>

                        <!-- City of Most Lived in -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="city_of_most_lived_in">City of Most Lived in</label>
                                <select class="form-control select2" id="city_of_most_lived_in"
                                        name="city_of_most_lived_in" disabled>
                                    @include('shared.option-list', ['options' => $mostLivedInCities, 'addEmptyOption' => true, 'selectedValue' => $selectedMostLivedInCityId])
                                </select>
                            </div>
                        </div>

                        <!-- Other Address Field -->
                        <div class="col-12 col-lg-3 col-6">
                            <div class="form-group">
                                <label for="city_of_birth_other">Other</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Other Location"
                                        value="{{ $philanthropist->city_of_most_lived_in_other }}"
                                        name="city_of_birth_other"
                                        id="city_of_birth_other"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>
                    <!-- Biography -->
                    <div class="row">
                        <!-- Biography -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="biography">Biography</label>
                                <textarea id="biography" name="biography" class="form-control"
                                          rows="4" disabled>{{$philanthropist->biography}}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Family Tree-->
                    <div class="row">
                        <div class="col-12">
                            <h3>Family Tree:</h3>
                        </div>
                    </div>
                    <div class="family-tree-repeater">
                        <div data-repeater-list="group_family_tree">
                            <div data-repeater-item class="family-tree-repeater-repeater-item" style="display:none;">
                                <input type="hidden" name="record_id" class="record-id" value=""/>
                                <!-- Relationship -->
                                <div class="row">
                                    <div class="col-12 col-lg-4 col-4">
                                        <div class="form-group">
                                            <label>Relationship</label>
                                            <select class="form-control dynamic-select2" name="relation_type" disabled>
                                                @include('shared.option-list', ['options' => $relationTypes, 'addEmptyOption' => true])
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Relation First Name -->
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input
                                                    type="text"
                                                    class="form-control relation-firstname"
                                                    placeholder="First Name"
                                                    value=""
                                                    name="relation_firstname"
                                                    disabled
                                            />
                                        </div>
                                    </div>

                                    <!-- Relation Last Name -->
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input
                                                    type="text"
                                                    class="form-control relation-lastname"
                                                    placeholder="Last Name"
                                                    value=""
                                                    name="relation_lastname"
                                                    disabled
                                            />
                                        </div>
                                    </div>
                                </div>
                                <!-- Search From Database -->
                                <div class="row mt-1">
                                    <div class="col-12">
                                        <strong>Search from database:</strong>
                                        <div class="row row-search-from-database">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>Select
                                                        Philanthropists</label>
                                                    <select class="form-control dynamic-remote-select2 search-from-database"
                                                            name="family_tree_selected_philanthropist"
                                                            disabled>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-auto col-12 view-philanthropist d-none">
                                                <div class="form-group">
                                                    <a class="d-flex btn btn-outline-primary text-nowrap mt-0 mt-md-2"
                                                       target="_blank">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- Search From Database END-->
                            </div>
                            <!-- Repeater Item END -->
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon thm-btn button-family-tree-repeater-add-more"
                                        type="button"
                                        data-repeater-create disabled>
                                    <i data-feather="plus" class="mr-25"></i>
                                    <span>Add More</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Personal Information END -->

        <!-- Business & Industry -->
        <section id="business_industry">
            <div class="card">
                <h2 class="card-header">BUSINESS & INDUSTRY</h2>
                <div class="card-body">
                    <div class="row">
                        <!-- Business Name -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="business_name">Business Name</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Business Name"
                                        value="{{$philanthropist->business()->first() ? $philanthropist->business()->first()->name : ''}}"
                                        name="business_name"
                                        id="business_name"
                                        disabled
                                />
                            </div>
                        </div>

                        <!-- Industry -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="industry-id">Industry</label>
                                <select class="form-control select2" id="industry-id" name="business_industry" disabled>
                                    @include('shared.option-list', ['options' => $industries, 'addEmptyOption' => true, 'selectedValue' => $philanthropist->business()->first() ? $philanthropist->business()->first()->industry_id : null])
                                </select>
                            </div>
                        </div>

                        <!-- Industry Other -->
                        <div class="col-12 col-md-2 col-lg-4">
                            <div class="form-group">
                                <label for="industry_other">Industry (Other)</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Industry Name"
                                        value="{{$philanthropist->business()->first() ? $philanthropist->business()->first()->industry_other : ''}}"
                                        name="business_industry_other"
                                        id="industry_other"
                                        disabled
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Business Details -->
                        <div class="col-md-2 col-lg-7 col-xl-6 col-12">
                            <div class="form-group">
                                <label for="business_details">Business Details</label>
                                <textarea id="business_details" name="business_details" class="form-control"
                                          rows="3" disabled>{{$philanthropist->business()->first() ? $philanthropist->business()->first()->details : ''}}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Business people closely associated with -->
                    <div class="row">
                        <div class="col-12">
                            <h3>Business people closely associated with:</h3>
                            <div class="business-people-repeater">
                                <div data-repeater-list="group_business_people">
                                    <div data-repeater-item class="business-people-repeater-repeater-item"
                                         style="display: none">
                                        <input type="hidden" name="record_id" value="" class="record-id"/>
                                        <div class="row d-flex align-items-end">
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input
                                                            name="business_people_firstname"
                                                            type="text"
                                                            class="form-control closely-associated-people-firstname"
                                                            aria-describedby="closely-associated-people-firstname"
                                                            placeholder="First Name"
                                                            disabled
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input
                                                            name="business_people_lastname"
                                                            type="text"
                                                            class="form-control closely-associated-people-lastname"
                                                            aria-describedby="closely-associated-people-lastname"
                                                            placeholder="Last Name"
                                                            disabled
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Search From Database -->
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <strong>Search from database:</strong>
                                                <div class="row row-search-from-database">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>Select
                                                                Philanthropists</label>
                                                            <select class="form-control dynamic-remote-select2 search-from-database"
                                                                    name="business_people_selected_philanthropist"
                                                                    disabled>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto col-12 view-philanthropist d-none">
                                                        <div class="form-group">
                                                            <a class="d-flex btn btn-outline-primary text-nowrap mt-0 mt-md-2"
                                                               target="_blank">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Search From Database END-->
                                        <hr/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-icon thm-btn button-business-people-repeater-add-more"
                                                type="button"
                                                data-repeater-create disabled>
                                            <i data-feather="plus" class="mr-25"></i>
                                            <span>Add More</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Associated People Modal -->
            <div id="delete-modal" class="modal fade modal-danger">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="delete-modal-title">DELETE BUSINESS PEOPLE</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <span class="font-weight-bold"
                                                                  id="delete-modal-record-name"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger delete-modal-button-delete"
                                    data-dismiss="modal">DELETE
                            </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Associated People Modal END -->

        </section>
        <!-- Business & Industry END -->

        <!-- Institutions -->
        <section id="institutions">
            <div class="card">
                <h2 class="card-header">INSTITUTIONS</h2>
                <div class="card-body">
                    <div class="founders-supporters-repeater">
                        <div data-repeater-list="group_founders_supporters">
                            <div data-repeater-item class="institutions-founder-repeater-item" style="display: none">
                                <input type="hidden" name="record_id" class="record-id" value=""/>
                                <div class="row">
                                    <div class="col-12">
                                        <h3>One of the founders/supporters of the following institutions:</h3>
                                    </div>
                                    <!-- Institution Name -->
                                    <div class="col-12 col-lg-4 col-4">
                                        <div class="form-group">
                                            <label>Institution Name</label>
                                            <select class="form-control dynamic-select2 founders_supporters_institution_name"
                                                    name="founders_supporters_institution_name" disabled>
                                                @include('shared.option-list', ['options' => $institutions, 'addEmptyOption' => true])
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Other Institution Field -->
                                    <div class="col-12 col-lg-3 col-6">
                                        <div class="form-group">
                                            <label>Other</label>
                                            <input
                                                    type="text"
                                                    class="form-control founders_supporters_institution_other"
                                                    placeholder="Other"
                                                    value=""
                                                    name="founders_supporters_institution_other"
                                                    disabled
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Country - Founders-->
                                    <div class="col-md-3 col-lg-3 col-12 date-input-small">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select class="form-control dynamic-select2"
                                                    name="founders_supporters_country"
                                                    disabled>
                                                @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true])
                                            </select>
                                        </div>
                                    </div>

                                    <!-- State - Founders-->
                                    <div class="col-md-3 col-lg-3 col-12 date-input-small">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select class="form-control dynamic-select2 dynamic-state"
                                                    name="founders_supporters_state"
                                                    disabled>
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- City - Founders-->
                                    <div class="col-md-3 col-lg-3 col-12 date-input-small">
                                        <div class="form-group">
                                            <label>City</label>
                                            <select class="form-control dynamic-select2"
                                                    name="founders_supporters_city"
                                                    disabled>
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <!-- Role -->
                                    <div class="col-md-3 col-lg-3 col-4">
                                        <div class="form-group">
                                            <label>Institution Role</label>
                                            <select class="form-control dynamic-select2-required"
                                                    name="founders_supporters_institution_role"
                                                    disabled>
                                                @include('shared.option-list', ['options' => $institutionRoles])
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-3 col-lg-3 col-12 date-input-small">
                                        <div class="form-group" id="form-group-role">
                                            <label class="d-block">Role</label>
                                            <div class="custom-control custom-radio custom-control-inline my-50">
                                                <input
                                                        type="radio"
                                                        id="founder"
                                                        name="institution-role"
                                                        class="custom-control-input"
                                                        required
                                                />
                                                <label class="custom-control-label" for="founder">Founder</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input
                                                        type="radio"
                                                        id="supporter"
                                                        name="institution-role"
                                                        class="custom-control-input"
                                                        required
                                                />
                                                <label class="custom-control-label" for="supporter">Supporter</label>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- Institution Type -->
                                    <div class="col-md-3 col-lg-3 col-4">
                                        <div class="form-group">
                                            <label>Institution Type</label>
                                            <select class="form-control dynamic-select2-required"
                                                    name="founders_supporters_institution_type"
                                                    disabled>
                                                @include('shared.option-list', ['options' => $institutionTypes])
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon thm-btn button-founders-supporters-repeater-add-more"
                                        type="button"
                                        data-repeater-create disabled>
                                    <i data-feather="plus" class="mr-25"></i>
                                    <span>Add More</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </form>
    <section id="upload-files" class="{{$philanthropistFiles == null ? 'd-none' : ''}}">
        <div class="card">
            <h2 class="card-header">FILES</h2>
            <div class="card-body">
                <div class="row">
                    <!-- Basic Tree -->
                    <div class="col-md-6 col-12 mt-2 mt-sm-2 mt-md-0">
                        <div id="jstree-basic">
                            <ul>
                                @if($philanthropistFiles != null)
                                    @foreach($philanthropistFiles as $ext => $files)
                                        <li data-jstree='{"icon" : "far fa-folder"}' class="jstree-open">
                                            {{$ext}}
                                            <ul>
                                                @foreach($files as $file)
                                                    <li data-jstree='{"icon" : "{{ $file['icon']}}"}'>
                                                        <a class="file-redirect" data-pfile="{{$file['phiFileId']}}" }
                                                           href="{{$file['path']}}">{{$file['file']}} <span
                                                                    class="font-small-2 font-weight-bold">({{$file['date']}})</span><span
                                                                    class="font-small-2 font-weight-bold"> <div
                                                                        style="margin-left: 7px"><span
                                                                            class="text-dark">{{$file['size']}}</span>  <span
                                                                            class="file-tag text-primary"
                                                                            id="tag-{{$file['tagId']}}">#{{ $file['tag']}}</span></div></span></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        @if($philanthropistFiles)
                            <div class="ml-2 mt-1">
                                <button class="btn btn-outline-primary" id="button-file-view" disabled>View</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
    <script src="{{ asset(mix('vendors/js/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/jstree.min.js')) }}"></script>



@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-repeater.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
    {{--    <script src="{{ asset(mix('js/scripts/extensions/ext-component-tree.js')) }}"></script>--}}

    <script>
        Dropzone.autoDiscover = false;
        $("#files").dropzone({
            url: "{{route('philanthropist-files.upload', $philanthropist->id)}}",
            // maxFilesize: 209715200,
            // acceptedFiles: "video/*",
            // addRemoveLinks: true,
            // dataType: "HTML",
            timeout: 30 * 60 * 1000, //30min
            success: function (file, response, data) {
                // Do things on Success
                file.previewElement.classList.add("dz-success");
            },
            error: function (file, response) {
                file.previewElement.classList.add("dz-error");
            }
        });

        //#region Globals
        const fileDeleteUrl = '{{route('philanthropist-files.remove', '@pFileId')}}';
        const fileTagChangeUrl = '{{route('philanthropist-files.changeFileTag', '@pFileId')}}'
        //#endregion
        //#region Utilities
        const decodeHtml = (str) => {
            var map = {
                '&amp;': '&',
                '&lt;': '<',
                '&gt;': '>',
                '&quot;': '"',
                '&#039;': "'"
            };
            return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function (m) {
                return map[m];
            });
        }
        const deserializeJSON = (strJson) => JSON.parse(decodeHtml(strJson));

        const getDynamicElement = (groupName, fieldName, index) => {
            const selector = `[name="${groupName}[${index}][${fieldName}]"]`;
            return $(selector);
        }

        const refreshFeatherIcons = () => {
            if (feather) {
                feather.replace({width: 14, height: 14});
            }
        }
        //#endregion

        const saveButtonClickHandler = (event) => {
            event.preventDefault();
            $('#form-save').submit();
        }

        const getPhilanthropistOptionWithTemplate = (relatedPhilanthropist) => {
            let template = '$firstname $lastname ($birth - $death)';
            template = template.replace('$firstname', relatedPhilanthropist.firstname ? relatedPhilanthropist.firstname : '');
            template = template.replace('$lastname', relatedPhilanthropist.lastname ? relatedPhilanthropist.lastname : '');
            template = template.replace('$birth', relatedPhilanthropist.year_of_birth ? relatedPhilanthropist.year_of_birth : '');
            template = template.replace('$death', relatedPhilanthropist.year_of_death ? relatedPhilanthropist.year_of_death : 'Unknown');

            if (relatedPhilanthropist.business != null && relatedPhilanthropist.business.length > 0) {
                template += ' - $business_name ($business_industry)';
                template = template.replace('$business_name', relatedPhilanthropist.business[0].name.toUpperCase());

                if (relatedPhilanthropist.business[0].industry != null) {
                    template = template.replace('$business_industry', relatedPhilanthropist.business[0].industry.name);
                } else {
                    template = template.replace('$business_industry', relatedPhilanthropist.business[0].industry_other);
                }
            }
            return template;
        }

        const remoteSelect2Initializer = {
            placeholder: 'Select',
            allowClear: true,
            ajax: {
                url: '{{route('common.philanthropists.searchForOption')}}',
                type: 'POST',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        "_token": '{{csrf_token()}}',
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.items,
                    };
                }
            }
        }

        $(document).ready(() => {
            $('.select2').select2({
                placeholder: 'Select',
                allowClear: true
            });

            $('#select-file-tag').select2({
                placeholder: 'Select'
            });

            $('.select2-status').select2({
                placeholder: 'Select',
                allowClear: false
            });

            const fileTree = $('#jstree-basic').jstree();

            $('#jstree-basic').on("changed.jstree", function (e, data) {

                $('#button-file-view').unbind('click');

                const selectedAnchor = $(`#${data.selected[0]}_anchor`);
                const fileUrl = selectedAnchor.attr('href');
                if (fileUrl && fileUrl != '#') {
                    const selectedTagId = selectedAnchor.find('.file-tag').attr('id').replace('tag-', '');
                    const selectedAnchorId = selectedAnchor.attr('id');
                    const philanthropistId = $('#philanthropist_id').val();
                    const philanthropistFileId = selectedAnchor.data('pfile');


                    $('#button-file-view').click(() => {
                        window.open(fileUrl, '_blank');
                    });


                    $('#button-file-view').removeAttr('disabled');
                    $('#button-file-delete').removeAttr('disabled');

                    // window.open($(`#${data.selected[0]}`).find('.file-redirect').attr('href'), '_blank');
                } else {
                    $('#button-file-view').attr('disabled', true);
                }
            });

            $('#form-save').validate();

            //#region Family Tree Dynamic Forms
            const strRelatedPeoples = '{{$relatedPeoples}}';
            if (strRelatedPeoples != '') {
                const relatedPeoples = JSON.parse(@json($relatedPeoples));
                // const relatedPeoples = deserializeJSON(strRelatedPeoples);
                for (const relatedPeopleIndex in relatedPeoples) {
                    $('.button-family-tree-repeater-add-more').click();
                    const relatedPeople = relatedPeoples[relatedPeopleIndex];
                    const fieldIndex = parseInt(relatedPeopleIndex) + 1;
                    const relationType = getDynamicElement('group_family_tree', 'relation_type', fieldIndex);
                    const firstname = getDynamicElement('group_family_tree', 'relation_firstname', fieldIndex);
                    const lastname = getDynamicElement('group_family_tree', 'relation_lastname', fieldIndex);
                    const selectedPhilanthropist = getDynamicElement('group_family_tree', 'family_tree_selected_philanthropist', fieldIndex);
                    const recordId = getDynamicElement('group_family_tree', 'record_id', fieldIndex);

                    recordId.val(relatedPeople.id);
                    relationType.val(relatedPeople.relation_type_id).trigger('change');


                    if (relatedPeople.related_philanthropist_id != null) {
                        var selectedOption = new Option(getPhilanthropistOptionWithTemplate(relatedPeople.related_philanthropist), relatedPeople.related_philanthropist_id, false, false);
                        selectedPhilanthropist.append(selectedOption).trigger('change');
                        firstname.val(relatedPeople.related_philanthropist.firstname);
                        lastname.val(relatedPeople.related_philanthropist.lastname);
                    } else {
                        firstname.val(relatedPeople.firstname);
                        lastname.val(relatedPeople.lastname);
                    }
                }
            } else {
                $('.button-family-tree-repeater-add-more').click();
            }
            //#endregion

            //#region Business People Associated People Dynamic Forms
            const associatedPeoples = JSON.parse(@json($associatedPeoples));
            if (associatedPeoples != null) {
                for (const associatedPeopleIndex in associatedPeoples) {
                    $('.button-business-people-repeater-add-more').click();
                    const associatedPeople = associatedPeoples[associatedPeopleIndex];
                    const fieldIndex = parseInt(associatedPeopleIndex) + 1;
                    const firstname = getDynamicElement('group_business_people', 'business_people_firstname', fieldIndex);
                    const lastname = getDynamicElement('group_business_people', 'business_people_lastname', fieldIndex);
                    const recordId = getDynamicElement('group_business_people', 'record_id', fieldIndex);

                    recordId.val(associatedPeople.id);


                    if (associatedPeople.associated_philanthropist != null) {
                        const selectedPhilanthropist = getDynamicElement('group_business_people', 'business_people_selected_philanthropist', fieldIndex);
                        var selectedOption = new Option(getPhilanthropistOptionWithTemplate(associatedPeople.associated_philanthropist), associatedPeople.associated_philanthropist_id, false, false);
                        selectedPhilanthropist.append(selectedOption).trigger('change');
                        firstname.val(associatedPeople.associated_philanthropist.firstname);
                        lastname.val(associatedPeople.associated_philanthropist.lastname);
                    } else {
                        firstname.val(associatedPeople.firstname);
                        lastname.val(associatedPeople.lastname);
                    }
                }
            } else {
                $('.button-business-people-repeater-add-more').click();
            }
            //#endregion

            //#region INSTITUTIONS

            {{--const strPhilanthropistInstitutions = '{{$philanthropistInstitutions}}';--}}
            const philanthropistInstitutions = JSON.parse(@json($philanthropistInstitutions));
            if (philanthropistInstitutions != null) {
                for (const philanthropistInstitutionIndex in philanthropistInstitutions) {
                    $('.button-founders-supporters-repeater-add-more').click();
                    const philanthropistInstitution = philanthropistInstitutions[philanthropistInstitutionIndex];

                    const fieldIndex = parseInt(philanthropistInstitutionIndex) + 1;
                    const institutionOther = getDynamicElement('group_founders_supporters', 'founders_supporters_institution_other', fieldIndex);
                    institutionOther.val(philanthropistInstitution.institution_other);

                    const recordId = getDynamicElement('group_founders_supporters', 'record_id', fieldIndex);
                    recordId.val(philanthropistInstitution.id);

                    if (philanthropistInstitution.institution_id != null) {
                        const institution = getDynamicElement('group_founders_supporters', 'founders_supporters_institution_name', fieldIndex);
                        institution.val(philanthropistInstitution.institution_id).trigger('change');
                    }

                    if (philanthropistInstitution.city != null) {
                        const country = getDynamicElement('group_founders_supporters', 'founders_supporters_country', fieldIndex);
                        country.val(philanthropistInstitution.city.country_id).trigger('change');
                        const state = getDynamicElement('group_founders_supporters', 'founders_supporters_state', fieldIndex);
                        const city = getDynamicElement('group_founders_supporters', 'founders_supporters_city', fieldIndex);
                        fetchStateOptionsForDynamic(philanthropistInstitution.city.country_id, state, city, () => {
                            state.val(philanthropistInstitution.city.state_id).trigger('change');
                            fetchCityOptionsDynamic(philanthropistInstitution.city.state_id, city, () => {
                                city.val(philanthropistInstitution.city_id).trigger('change');
                            });
                        });
                    }

                    //group_founders_supporters[1][founders_supporters_institution_name]
                    if (philanthropistInstitution.institution_role_id != null) {
                        const institutionRole = getDynamicElement('group_founders_supporters', 'founders_supporters_institution_role', fieldIndex);
                        institutionRole.val(philanthropistInstitution.institution_role_id).trigger('change');
                    }

                    if (philanthropistInstitution.institution_type_id != null) {
                        const institutionType = getDynamicElement('group_founders_supporters', 'founders_supporters_institution_type', fieldIndex);
                        institutionType.val(philanthropistInstitution.institution_type_id).trigger('change');
                    }
                }
            } else {
                $('.button-founders-supporters-repeater-add-more').click();
            }
        });

        const searchFromDatabaseChangedHandler = (event) => {
            const viewElement = $(event.target).parents('.row-search-from-database').find('.view-philanthropist');
            if (viewElement) {
                if (event.target.value) {
                    let viewUrl = "{{route('philanthropists.edit', '@')}}";
                    viewUrl = viewUrl.replace('@', event.target.value);
                    viewElement.find('a').attr('href', viewUrl);
                    viewElement.removeClass('d-none');
                } else {
                    viewElement.addClass('d-none');
                }
            }
        }

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

        const getPeopleNameRepeaterValues = () => {
            const repeaterItem = $('.business-people-repeater-repeater-item');
            const dataList = [];
            for (const repeatingItem of repeaterItem) {
                const firstNameElement = repeatingItem.querySelector('#closely-associated-people-firstname');
                const lastNameElement = repeatingItem.querySelector('#closely-associated-people-lastname');
                if (firstNameElement) {
                    const data = {
                        firstName: firstNameElement.value,
                        lastName: lastNameElement && lastNameElement.value
                    }
                    dataList.push(data);
                }
            }
            return dataList;
        }

        const getPhilanthropistsIdSelectValues = () => {
            const philanthropistsIdList = [];
            const selectedPhilanthropists = $('#search-db-philanthropists-id').find(':selected');
            for (const selectedPhilanthropist of selectedPhilanthropists) {
                philanthropistsIdList.push(selectedPhilanthropist.value);
            }
            return philanthropistsIdList;
        }


        //#region Country, Sate, City
        const countryChangedHandler = (event) => {
            const countryId = event.target.value;
            let stateOfBirth = null;
            let cityOfBirth = null;

            switch (event.target.id) {
                case 'country-of-birth': {
                    stateOfBirth = $('#state-of-birth');
                    cityOfBirth = $('#city_of_birth');
                    break;
                }
                case 'country_of_most_lived_in': {
                    stateOfBirth = $('#state_of_most_lived_in');
                    cityOfBirth = $('#city_of_most_lived_in');
                    break;
                }
            }

            if (!countryId) {
                stateOfBirth.html('');
                cityOfBirth.html('');
                return;
            }
            // stateOfBirth.prop('disabled', true);
            // cityOfBirth.prop('disabled', true);

            fetchStateOptions(countryId, event.target.id);
        }

        const fetchStateOptions = (countryId, targetElementId) => {
            let stateOfBirth = null;
            let cityOfBirth = null

            switch (targetElementId) {
                case 'country-of-birth': {
                    stateOfBirth = $('#state-of-birth');
                    cityOfBirth = $('#city_of_birth');
                    break;
                }
                case 'country_of_most_lived_in': {
                    stateOfBirth = $('#state_of_most_lived_in');
                    cityOfBirth = $('#city_of_most_lived_in');
                    break;
                }
            }
            $.ajax({
                url: '{{route('address.states')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    countryId: countryId
                },
                cache: false,
                success: function (response) {
                    stateOfBirth.html(response);
                    // stateOfBirth.prop('disabled', false);
                    // cityOfBirth.prop('disabled', false);
                    setTimeout(() => {
                        stateOfBirth.select2('open');
                    }, 100);

                },
                error: function (response) {
                    console.log('error', response);
                },
            });
        }

        const stateChangedHandler = (event) => {
            const stateId = event.target.value;
            const targetElementId = event.target.id;
            if (!stateId) {
                switch (targetElementId) {
                    case 'state-of-birth': {
                        $('#city_of_birth').html('');
                        break;
                    }
                    case 'state_of_most_lived_in': {
                        $('#city_of_most_lived_in').html('');
                        break;
                    }
                }
                return;
            }
            // $('#city-founders').prop('disabled', true);
            fetchCityOptions(stateId, targetElementId);
        }

        const fetchCityOptions = (stateId, targetElementId) => {
            let cityOfBirth = null;
            switch (targetElementId) {
                case 'state-of-birth': {
                    cityOfBirth = $('#city_of_birth');
                    break;
                }
                case 'state_of_most_lived_in': {
                    cityOfBirth = $('#city_of_most_lived_in');
                    break;
                }
            }
            $.ajax({
                url: '{{route('address.cities')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    stateId: stateId
                },
                cache: false,
                success: function (response) {
                    cityOfBirth.html(response);
                    cityOfBirth.prop('disabled', false);
                    setTimeout(() => {
                        cityOfBirth.select2('open');
                    }, 100);
                },
                error: function (response) {
                    console.log('error', response);
                },
            });
        }

        const countryChangedHandlerDynamic = (event) => {
            const stateSelector = event.target.name
                .replace('country', 'state')
                .replace('[', '\\[')
                .replace(']', '\\]');

            const citySelector = event.target.name
                .replace('country', 'city')
                .replace('[', '\\[')
                .replace(']', '\\]');

            const dependentState = $('select[name="' + stateSelector + '"]');
            const dependentCity = $('select[name="' + citySelector + '"]');

            const countryId = event.target.value;
            if (!countryId) {
                dependentState.html('');
                dependentCity.html('');
                return;
            }

            dependentState.prop('disabled', true);
            dependentCity.prop('disabled', true);

            fetchStateOptionsForDynamic(countryId, dependentState, dependentCity, () => {
                setTimeout(() => {
                    dependentState.select2('open');
                }, 200);
            });
        }

        const fetchStateOptionsForDynamic = (countryId, dependentState, dependentCity, callback = null) => {
            $.ajax({
                url: '{{route('address.states')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    countryId: countryId
                },
                cache: false,
                success: function (response) {
                    dependentState.html(response);
                    dependentState.prop('disabled', false);
                    dependentCity.prop('disabled', false);
                    if (callback != null) {
                        callback();
                    }
                },
                error: function (response) {
                    console.log('error', response);
                },
            });
        }

        const stateChangedHandlerDynamic = (event) => {
            const citySelector = event.target.name
                .replace('state', 'city')
                .replace('[', '\\[')
                .replace(']', '\\]');
            const dependentCity = $('select[name="' + citySelector + '"]')

            const stateId = event.target.value;
            if (!stateId) {
                dependentCity.html('');
                return;
            }
            dependentCity.prop('disabled', true);
            fetchCityOptionsDynamic(stateId, dependentCity, () => {
                setTimeout(() => {
                    dependentCity.select2('open');
                }, 200);
            });
        }

        const fetchCityOptionsDynamic = (stateId, dependentCity, callback = null) => {
            $.ajax({
                url: '{{route('address.cities')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    stateId: stateId
                },
                cache: false,
                success: function (response) {
                    dependentCity.html(response);
                    dependentCity.prop('disabled', false);
                    if (callback != null) {
                        callback();
                    }
                },
                error: function (response) {
                    console.log('error', response);
                },
            });
        }
        //endregion

        //#region Repeater Initialize

        $('.business-people-repeater').repeater({
            show: function () {
                // if ($('.button-business-people-delete').length === 1) {
                //     $('.button-business-people-delete').addClass('d-none');
                // }
                const repeatedItem = $(this);
                repeatedItem.find('.dynamic-select2').select2({
                    placeholder: 'Select',
                    allowClear: true
                });

                repeatedItem.find('.dynamic-remote-select2').select2(remoteSelect2Initializer);

                const selectSearchFromDatabase = repeatedItem.find('.search-from-database')[0];
                const firstNameFieldName = selectSearchFromDatabase.name.replace('business_people_selected_philanthropist', 'business_people_firstname');
                const lastNameFieldName = selectSearchFromDatabase.name.replace('business_people_selected_philanthropist', 'business_people_lastname');


                repeatedItem.find('.search-from-database').on('select2:select', function (e) {
                    const pattern = /([A-Za-z0-9\. -]+)(?= \()/i;
                    const fullName = e.params.data.text.match(pattern);
                    const seperatedName = fullName[0].split(' ');
                    let firstName = '', lastName = '';

                    if (seperatedName.length > 0) {
                        if (seperatedName.length == 1) {
                            firstName = fullName;
                        } else if (seperatedName.length == 2) {
                            firstName = seperatedName[0];
                            lastName = seperatedName[1];
                        } else if (seperatedName.length == 3) {
                            firstName = seperatedName[0] + ' ' + seperatedName[1];
                            lastName = seperatedName[2];
                        } else {
                            firstName = seperatedName[0];
                            for (let i = 1; i < seperatedName.length; i++) {
                                lastName += seperatedName[i] + ' ';
                            }
                        }
                    }

                    $('input[name="' + firstNameFieldName + '"]').val(firstName);
                    $('input[name="' + lastNameFieldName + '"]').val(lastName);
                });

                refreshFeatherIcons();
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                const deletingElement = $(this);
                const deletingFirstName = deletingElement.find('.closely-associated-people-firstname');
                const deletingLastName = deletingElement.find('.closely-associated-people-lastname');
                const recordId = deletingElement.find('.record-id').val();
                if (deletingFirstName.val()) {
                    $('#delete-modal-title').text('DELETE BUSINESS PEOPLE');
                    $('#delete-modal-record-name').text(`${deletingFirstName.val()} ${deletingLastName && deletingLastName.val()}`);
                    $('#delete-modal').modal();

                    const deleteButton = $('.delete-modal-button-delete');
                    deleteButton.off('click');
                    deleteButton.bind('click', () => {
                        if (recordId) {
                            const deletedAssociatedPeoples = $('#deleted_associated_peoples')
                            if (deletedAssociatedPeoples.val()) {
                                deletedAssociatedPeoples.val(deletedAssociatedPeoples.val() + ',' + recordId);
                            } else {
                                deletedAssociatedPeoples.val(recordId);
                            }
                        }
                        $(this).slideUp(deleteElement);
                    });
                    return;
                }
                if (recordId) {
                    const deletedAssociatedPeoples = $('#deleted_associated_peoples')
                    if (deletedAssociatedPeoples.val()) {
                        deletedAssociatedPeoples.val(deletedAssociatedPeoples.val() + ',' + recordId);
                    } else {
                        deletedAssociatedPeoples.val(recordId);
                    }
                }
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $('.family-tree-repeater').repeater({
            show: function () {
                // if ($('.button-family-tree-delete').length == 1) {
                //     $('.button-family-tree-delete').addClass('d-none');
                // }

                const repeatedItem = $(this);
                repeatedItem.find('.dynamic-select2').select2({
                    placeholder: 'Select',
                    allowClear: true
                });

                repeatedItem.find('.dynamic-remote-select2').select2(remoteSelect2Initializer);

                const selectSearchFromDatabase = repeatedItem.find('.search-from-database')[0];
                const firstNameFieldName = selectSearchFromDatabase.name.replace('family_tree_selected_philanthropist', 'relation_firstname');
                const lastNameFieldName = selectSearchFromDatabase.name.replace('family_tree_selected_philanthropist', 'relation_lastname');

                repeatedItem.find('.search-from-database').on('select2:select', function (e) {
                    const pattern = /([A-Za-z0-9\. -]+)(?= \()/i;
                    const fullName = e.params.data.text.match(pattern);
                    const seperatedName = fullName[0].split(' ');
                    let firstName = '', lastName = '';

                    if (seperatedName.length > 0) {
                        if (seperatedName.length == 1) {
                            firstName = fullName;
                        } else if (seperatedName.length == 2) {
                            firstName = seperatedName[0];
                            lastName = seperatedName[1];
                        } else if (seperatedName.length == 3) {
                            firstName = seperatedName[0] + ' ' + seperatedName[1];
                            lastName = seperatedName[2];
                        } else {
                            firstName = seperatedName[0];
                            for (let i = 1; i < seperatedName.length; i++) {
                                lastName += seperatedName[i] + ' ';
                            }
                        }
                    }

                    $('input[name="' + firstNameFieldName + '"]').val(firstName);
                    $('input[name="' + lastNameFieldName + '"]').val(lastName);
                });

                refreshFeatherIcons();
                $(this).slideDown(55);
            },
            hide: function (deleteElement) {
                const deletingElement = $(this);
                const deletingFirstName = deletingElement.find('.relation-firstname');
                const deletingLastName = deletingElement.find('.relation-lastname');
                const recordId = deletingElement.find('.record-id').val();

                if (deletingFirstName.val()) {
                    $('#delete-modal-title').text('DELETE FAMILY MEMBER');
                    $('#delete-modal-record-name').text(`${deletingFirstName.val()} ${deletingLastName && deletingLastName.val()}`);
                    $('#delete-modal').modal();

                    const deleteButton = $('.delete-modal-button-delete');
                    console.log(deleteButton);
                    deleteButton.off('click');
                    deleteButton.bind('click', () => {
                        if (recordId) {
                            const deletingRelatedPeoples = $('#deleted_related_peoples')
                            if (deletingRelatedPeoples.val()) {
                                deletingRelatedPeoples.val(deletingRelatedPeoples.val() + ',' + recordId);
                            } else {
                                deletingRelatedPeoples.val(recordId);
                            }
                        }
                        $(this).slideUp(deleteElement);
                    });
                    return;
                }
                if (recordId) {
                    const deletingRelatedPeoples = $('#deleted_related_peoples')
                    if (deletingRelatedPeoples.val()) {
                        deletingRelatedPeoples.val(deletingRelatedPeoples.val() + ',' + recordId);
                    } else {
                        deletingRelatedPeoples.val(recordId);
                    }
                }
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $('.founders-supporters-repeater').repeater({
            show: function () {
                // if ($('.button-intitutions-founder-delete').length == 1) {
                //     $('.button-intitutions-founder-delete').addClass('d-none');
                // }

                const repeatedItem = $(this);
                repeatedItem.find('.dynamic-select2').select2({
                    placeholder: 'Select',
                    allowClear: true
                });

                repeatedItem.find('.dynamic-select2-required').select2({
                    placeholder: 'Select'
                });

                $(this).slideDown(55);
                refreshFeatherIcons();
            },
            hide: function (deleteElement) {
                const deletingElement = $(this);
                const recordId = deletingElement.find('.record-id').val();
                const selectedInstitution = deletingElement.find('.founders_supporters_institution_name');
                const institutionOther = deletingElement.find('.founders_supporters_institution_other');
                if (selectedInstitution.val() || institutionOther.val()) {
                    const institutionName = selectedInstitution.val() ? selectedInstitution.find('option:selected').text() : institutionOther.val();
                    $('#delete-modal-title').text('DELETE INSTITUTION');
                    $('#delete-modal-record-name').text(institutionName);
                    $('#delete-modal').modal();

                    const deleteButton = $('.delete-modal-button-delete');
                    deleteButton.off('click');
                    deleteButton.bind('click', () => {
                        if (recordId) {
                            const deletedInstitutions = $('#deleted_institutions')
                            if (deletedInstitutions.val()) {
                                deletedInstitutions.val(deletedInstitutions.val() + ',' + recordId);
                            } else {
                                deletedInstitutions.val(recordId);
                            }
                        }
                        $(this).slideUp(deleteElement);
                    });
                } else {
                    $(this).slideUp(deleteElement);
                }
            },
        });

        //#endregion


        $('#select-file-tag').on('select2:select', function (e) {
            const selectElement = $('#select-file-tag');
            const anchor = $('#' + selectElement.data('file_anchor'));
            const tagElement = anchor.find('.file-tag');
            changeFileTag(selectElement.data('pfile'), selectElement.val(), tagElement)
        });

        const changeFileTag = (philanthropistFileId, fileTagId, tagElement) => {
            console.log('philanthropistFileId', philanthropistFileId);
            console.log('fileTagId', fileTagId);
            console.log('tagElement', tagElement);
            console.log(fileTagChangeUrl.replace('@pFileId', philanthropistFileId));

            $.ajax({
                'url': fileTagChangeUrl.replace('@pFileId', philanthropistFileId),
                'type': 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    fileTagId: fileTagId
                },
                cache: false,
                success: function (response) {
                    console.log(response);
                    tagElement.text('#' + response.tagName);
                    tagElement.addClass('text-success');
                    tagElement.attr('id', `tag-${fileTagId}`);
                },
                error: function (err) {
                    console.log(err);
                },
            });
        };

    </script>
@endsection