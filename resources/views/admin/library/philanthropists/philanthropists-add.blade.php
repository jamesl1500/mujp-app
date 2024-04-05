@extends('layouts/contentLayoutMaster')

@section('title')
    @can('isAdmin')
        ADD PHILANTHROPIST
    @endcan
    @can('isMember')
        SUBMIT A PHILANTHROPIST
    @endcan
@endsection

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

        .file-upload {
            border: 2px dashed var(--thm-base);
            padding: 25px;
            text-align: left;
            cursor: no-drop;
        }

        .file-upload :hover {
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
    @include('shared.alert')

    <form id="form-save" method="POST" action="{{route('common.philanthropists.store')}}">
    @csrf

    <!-- Personal Information -->
        <section id="personal-information">
            <div class="card">
                <h2 class="card-header">PERSONAL INFORMATION</h2>
                <div class="card-body">
                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="First Name"
                                        value=""
                                        name="firstname"
                                        id="firstname"
                                        required
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
                                        value=""
                                        name="lastname"
                                        id="lastname"
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
                                           class="custom-control-input"/>
                                    <label class="custom-control-label"
                                           for="male">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="female" name="gender"
                                           value="female"
                                           class="custom-control-input"/>
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
                                <select class="form-control select2" id="month_of_birth" name="month_of_birth">
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}">{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-4 col-lg-2 col-4 date-input-small">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <select class="form-control select2" id="date_of_birth" name="date_of_birth">
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}">{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Year of Birth -->
                        <div class="col-md-4 col-lg-2 col-4 date-input-small">
                            <div class="form-group">
                                <label for="year_of_birth">Year of Birth</label>
                                <select class="form-control select2 year-select" id="year_of_birth" required
                                        name="year_of_birth">
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}">{{ $year }}</option>
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
                                        name="jewish_month_of_birth">
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}">{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Date of Birth -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_date_of_birth">Jewish Date of Birth</label>
                                <select class="form-control select2" id="jewish_date_of_birth"
                                        name="jewish_date_of_birth">
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}">{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Year of Birth -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_year_of_birth">Jewish Year of Birth</label>
                                <select class="form-control select2 year-select" id="jewish_year_of_birth"
                                        name="jewish_year_of_birth">
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}">{{ $year }}</option>
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
                                <select class="form-control select2" id="month_of_death" name="month_of_death">
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}">{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Date of Death -->
                        <div class="col-4 col-md-4 col-lg-2  date-input-small">
                            <div class="form-group">
                                <label for="date_of_death">Date of Death</label>
                                <select class="form-control select2" id="date_of_death" name="date_of_death">
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}">{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Year of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="year_of_death">Year of Death</label>
                                <select class="form-control select2 year-select" id="year_of_death"
                                        name="year_of_death">
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}">{{ $year }}</option>
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
                                        name="jewish_month_of_death">
                                    <option></option>
                                    @for($month = 1; $month <= 12; $month++ )
                                        <option value="{{ $month }}">{{ str_pad($month,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Date of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_date_of_death">Jewish Date of Death</label>
                                <select class="form-control select2" id="jewish_date_of_death"
                                        name="jewish_date_of_death">
                                    <option></option>
                                    @for($date = 1; $date <= 31; $date++ )
                                        <option value="{{ $date }}">{{ str_pad($date,2,"0", STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Jewish Year of Death -->
                        <div class="col-4 col-md-4 col-lg-2 date-input-small">
                            <div class="form-group">
                                <label for="jewish_year_of_death">Jewish Year of Death</label>
                                <select class="form-control select2 year-select" id="jewish_year_of_death"
                                        name="jewish_year_of_death">
                                    <option></option>
                                    @for($year = 1700; $year <= date('Y'); $year++ )
                                        <option value="{{ $year }}">{{ $year }}</option>
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
                                <select class="form-control select2" name="country-of-birth" id="country-of-birth"
                                        oninput="countryChangedHandler(event)">
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true])
                                </select>
                            </div>
                        </div>

                        <!-- State of Birth -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="state-of-birth">State of Birth</label>
                                <select class="form-control select2" name="state-of-birth" id="state-of-birth"
                                        oninput="stateChangedHandler(event)">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <!-- City of Birth -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="city_of_birth">City of Birth</label>
                                <select class="form-control select2" id="city_of_birth" name="city_of_birth">
                                    <option></option>
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
                                        value=""
                                        name="city_of_birth_other"
                                        id="city_of_birth_other"
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
                                        id="country_of_most_lived_in"
                                        oninput="countryChangedHandler(event)">
                                    @include('shared.option-list', ['options' => $countries, 'addEmptyOption' => true])
                                </select>
                            </div>
                        </div>

                        <!-- State of Most Lived in -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="state_of_most_lived_in">State of Most Lived in</label>
                                <select class="form-control select2" name="state_of_most_lived_in"
                                        id="state_of_most_lived_in"
                                        oninput="stateChangedHandler(event)">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <!-- City of Most Lived in -->
                        <div class="col-md-4 col-lg-3 col-12 date-input-small">
                            <div class="form-group">
                                <label for="city_of_most_lived_in">City of Most Lived in</label>
                                <select class="form-control select2" id="city_of_most_lived_in"
                                        name="city_of_most_lived_in">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <!-- Other Address Field -->
                        <div class="col-12 col-lg-3 col-6">
                            <div class="form-group">
                                <label for="city_of_most_lived_in_other">Other</label>
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Other Location"
                                        value=""
                                        name="city_of_most_lived_in_other"
                                        id="city_of_most_lived_in_other"
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
                                          rows="4"></textarea>
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
                                <!-- Relationship -->
                                <div class="row">
                                    <div class="col-12 col-lg-4 col-4">
                                        <div class="form-group">
                                            <label>Relationship</label>
                                            <select class="form-control dynamic-select2" name="relation_type">
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
                                                    class="form-control"
                                                    placeholder="First Name"
                                                    value=""
                                                    name="relation_firstname"
                                            />
                                        </div>
                                    </div>

                                    <!-- Relation Last Name -->
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Last Name"
                                                    value=""
                                                    name="relation_lastname"
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
                                                            onchange="searchFromDatabaseChangedHandler(event)">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-auto col-12 view-philanthropist d-none">
                                                <div class="form-group">
                                                    <a class="d-flex btn btn-outline-primary text-nowrap mt-0 mt-md-2"
                                                       target="_blank">View</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <div class="d-flex mt-2 w-100">
                                                        <button class="btn btn-outline-danger text-nowrap button-family-tree-delete"
                                                                type="button" data-repeater-delete>
                                                            <i data-feather="minus"></i>
                                                            <span>Delete</span>
                                                        </button>
                                                    </div>
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
                                <button class="btn btn-icon thm-btn button-repeater-add-more" type="button"
                                        data-repeater-create>
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
                                        value=""
                                        name="business_name"
                                        id="business_name"
                                />
                            </div>
                        </div>

                        <!-- Industry -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="industry-id">Industry</label>
                                <select class="form-control select2" id="industry-id" name="business_industry">
                                    @include('shared.option-list', ['options' => $industries, 'addEmptyOption' => true])
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
                                        value=""
                                        name="business_industry_other"
                                        id="industry_other"
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
                                          rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h3>Business people closely associated with:</h3>
                            <div class="business-people-repeater">
                                <div data-repeater-list="group_business_people">
                                    <div data-repeater-item class="business-people-repeater-repeater-item"
                                         style="display: none">
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
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <div class="d-flex mt-2 w-100">
                                                        <button class="btn btn-outline-danger text-nowrap button-business-people-delete"
                                                                type="button" data-repeater-delete>
                                                            <i data-feather="minus"></i>
                                                            <span>Delete</span>
                                                        </button>
                                                    </div>
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
                                                                    onchange="searchFromDatabaseChangedHandler(event)">
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
                                        <button class="btn btn-icon thm-btn button-repeater-add-more" type="button"
                                                data-repeater-create>
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
            <div id="delete-associated-people-modal" class="modal fade modal-danger">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">DELETE BUSINESS PEOPLE</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <span class="font-weight-bold"
                                                                  id="deleting-associated-people-name"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-delete-associated-people"
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
                                <div class="row">
                                    <div class="col-12">
                                        <h3>One of the founders/supporters of the following institutions:</h3>
                                    </div>
                                    <!-- Institution Name -->
                                    <div class="col-12 col-lg-4 col-4">
                                        <div class="form-group">
                                            <label>Institution Name</label>
                                            <select class="form-control dynamic-select2"
                                                    name="founders_supporters_institution_name">
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
                                                    class="form-control"
                                                    placeholder="Other"
                                                    value=""
                                                    name="founders_supporters_institution_other"
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
                                                    oninput="countryChangedHandlerDynamic(event)">
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
                                                    oninput="stateChangedHandlerDynamic(event)">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- City - Founders-->
                                    <div class="col-md-3 col-lg-3 col-12 date-input-small">
                                        <div class="form-group">
                                            <label>City</label>
                                            <select class="form-control dynamic-select2"
                                                    name="founders_supporters_city">
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
                                            <select class="form-control dynamic-select2"
                                                    name="founders_supporters_institution_role">
                                                @include('shared.option-list', ['options' => $institutionRoles, 'addEmptyOption' => true])
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
                                            <select class="form-control dynamic-select2"
                                                    name="founders_supporters_institution_type">
                                                @include('shared.option-list', ['options' => $institutionTypes, 'addEmptyOption' => true])
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="form-group">
                                            <div class="d-flex mt-2 w-100">
                                                <button class="btn btn-outline-danger text-nowrap button-intitutions-founder-delete"
                                                        type="button" data-repeater-delete>
                                                    <i data-feather="minus"></i>
                                                    <span>Delete</span>
                                                </button>
                                                <!-- <button class="btn btn-outline-danger text-nowrap px-1 float-right"
                                                        data-repeater-delete type="button">
                                                    <i data-feather="x" class="mr-25"></i>
                                                    <span>Delete</span>
                                                </button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon thm-btn button-repeater-add-more" type="button"
                                        data-repeater-create>
                                    <i data-feather="plus" class="mr-25"></i>
                                    <span>Add More</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Associated People Modal -->
            <div id="delete-associated-people-modal" class="modal fade modal-danger">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">DELETE BUSINESS PEOPLE</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <span class="font-weight-bold"
                                                                  id="deleting-associated-people-name"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-delete-associated-people"
                                    data-dismiss="modal">DELETE
                            </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Associated People Modal END -->

        </section>
        <!-- Institutions-->

    @can('isAdmin')
        <!--Upload Files-->
            <section id="upload-files">
                <div class="card">
                    <h2 class="card-header">FILES</h2>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="file-upload">
                                    <h6>In order to upload files for this Philanthropists, please click Save All first
                                        and
                                        then the file upload section will be available.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endcan
        <section id="actions">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex">
                            <!-- <button id="button-save-all" class="btn btn-icon thm-btn" type="button"
                                     onclick="saveAllInformation(event);">
                                 <i data-feather="save" class="mr-25 ml-25"></i>
                                 <span class="pointer-events-none"> SAVE ALL</span>
                             </button> -->
                            <button id="button-save-all" class="btn btn-icon thm-btn" type="submit"
                                    onclick="//saveAllInformation(event);">
                                <i data-feather="save" class="mr-25 ml-25"></i>
                                <span class="pointer-events-none">SAVE ALL</span>
                            </button>
                            <div class="operation-spinner">
                            <span id="save-all-spinner"
                                  class="spinner-border text-primary flex-spinner d-none"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </form>



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


@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-repeater.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>


    <script>

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
            $('.button-repeater-add-more').click()
            $('#form-save').validate();
        });


        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true
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

        const saveAllInformation = (event) => {
            event.preventDefault();

            $('#form-save').valid();

            // if(!$('#form-save').valid()){
            //     $('.scroll-top').click();
            //     return;
            // }

            $.ajax({
                'url': "{{route('common.philanthropists.store')}}",
                'type': 'post',
                data: $('#form-save').serialize(),
                success: function (response) {
                    console.log('OK');
                },
                error: function (err) {
                    if (err.status == 422) { // when status code is 422, it's a validation issue
                        console.log(err.responseJSON);
                        // you can loop through the errors object and show it to the user
                        console.warn(err.responseJSON.errors);
                        // display errors on each form field
                        $.each(err.responseJSON.errors, function (i, error) {
                            const errorField = $('#' + i + '-error');
                            console.log(errorField);
                            var errorTemplate = `<span id="${i}-error" class="error">${error[0]}</span>`;

                            if (!errorField.length) {
                                var el = $(document).find('[name="' + i + '"]');
                                el.addClass('error');
                                if (el.get(0).tagName == 'SELECT') {
                                    el.parent().after(errorTemplate)
                                } else {
                                    el.after(errorTemplate);
                                }
                            } else {
                                errorField.text(error[0]);
                            }
                        });
                    }
                },
            });
            return;


            const saveAllSpinner = $('#save-all-spinner');
            saveAllSpinner.removeClass('d-none');
            $('#button-save-all').prop('disabled', true);

            const data = {
                firstName: $('#firstname').val(),
                lastName: $('#lastname').val(),
                yearOfBirth: $('#year_of_birth').val(),
                monthOfBirth: $('#month_of_birth').val(),
                dateOfBirth: $('#date_of_birth').val(),
                jewishYearOfBirth: $('#jewish_year_of_birth').val(),
                jewishMonthOfBirth: $('#jewish_month_of_birth').val(),
                jewishDateOfBirth: $('#jewish_date_of_birth').val(),
                yearOfDeath: $('#year_of_death').val(),
                monthOfDeath: $('#month_of_death').val(),
                dateOfDeath: $('#date_of_death').val(),
                jewishYearOfDeath: $('#jewish_year_of_death').val(),
                jewishMonthOfDeath: $('#jewish_month_of_death').val(),
                jewishDateOfDeath: $('#jewish_date_of_death').val(),
                countryOfBirth: $('#country-of-birth').val(),
                stateOfBirth: $('#state-of-birth').val(),
                cityOfBirth: $('#city_of_birth').val(),
                otherLocation: $('#city_of_birth_other').val(),
                businessName: $('#business_name').val(),
                industryId: $('#industry-id').val(),
                otherIndustry: $('#industry_other').val(),
                businessDetails: $('#business_details').val(),
                peopleAssociatedWithName: getPeopleNameRepeaterValues(),
                associatedPhilanthropists: getPhilanthropistsIdSelectValues()
            }

            setTimeout(() => {
                $('#button-save-all').prop('disabled', false);
                saveAllSpinner.addClass('d-none');
            }, 2000);
            console.log(data);

        }

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

        const getPhilanthropistsIdSelectValues = () => {
            const philanthropistsIdList = [];
            const selectedPhilanthropists = $('#search-db-philanthropists-id').find(':selected');
            for (const selectedPhilanthropist of selectedPhilanthropists) {
                philanthropistsIdList.push(selectedPhilanthropist.value);
            }
            return philanthropistsIdList;
        }

        const refreshFeatherIcons = () => {
            if (feather) {
                feather.replace({width: 14, height: 14});
            }
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
            stateOfBirth.prop('disabled', true);
            cityOfBirth.prop('disabled', true);

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
                success: function (response) {
                    stateOfBirth.html(response);
                    stateOfBirth.prop('disabled', false);
                    cityOfBirth.prop('disabled', false);
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
            })
        }

        const fetchCityOptionsDynamic = (stateId, dependentCity, callback = null) => {
            $.ajax({
                url: '{{route('address.cities')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    stateId: stateId
                },
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

        //#region Repeaters

        $('.business-people-repeater').repeater({
            show: function () {
                if ($('.button-business-people-delete').length === 1) {
                    $('.button-business-people-delete').addClass('d-none');
                }
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
                const deletingFormFormElements = deletingElement.children()[0];
                const deletingFirstName = deletingFormFormElements.querySelector('.closely-associated-people-firstname');
                const deletingLastName = deletingFormFormElements.querySelector('.closely-associated-people-lastname');
                if (deletingFirstName.value) {
                    $('#deleting-associated-people-name').text(`${deletingFirstName.value} ${deletingLastName && deletingLastName.value}`);
                    $('#delete-associated-people-modal').modal();

                    const deleteButton = $('.btn-delete-associated-people');
                    deleteButton.off('click');
                    deleteButton.bind('click', () => {
                        $(this).slideUp(deleteElement);
                    });
                    return;
                }
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $('.founders-supporters-repeater').repeater({
            show: function () {
                if ($('.button-intitutions-founder-delete').length == 1) {
                    $('.button-intitutions-founder-delete').addClass('d-none');
                }

                const repeatedItem = $(this);
                repeatedItem.find('.dynamic-select2').select2({
                    placeholder: 'Select',
                    allowClear: true
                });

                $(this).slideDown(55);
                refreshFeatherIcons();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $('.family-tree-repeater').repeater({
            show: function () {
                if ($('.button-family-tree-delete').length == 1) {
                    $('.button-family-tree-delete').addClass('d-none');
                }

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
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        //#endregion


    </script>
@endsection