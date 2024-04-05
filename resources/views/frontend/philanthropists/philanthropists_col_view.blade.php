@extends('layouts.frontend.master')

@section('title')
    Home
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
    <style>

        /* Favorite */
        .favorite-icon {
            font-size: 1.3rem;
            vertical-align: middle;
            line-height: 0;
            margin-bottom: 4px;
        }

        .favorite-icon:hover {
            color: var(--thm-base);
            cursor: pointer;
        }

        .favorite-selected {
            color: var(--thm-base) !important;
        }

        /* End of Favorite */


        .advanced-search__link {
            color: white;
            text-align: center;
            padding-right: 15px;
        }

        .advanced-search__title {
            color: white;
        }

        .advanced-search__container .form-group input, select, .select2-results__option {
            color: rgb(37, 41, 48) !important;
            border-radius: 0 !important;
        }

        .form-group input::placeholder, .select2-selection__placeholder {
            color: rgb(37, 41, 48) !important;
        }

        .advanced-search__link:hover {
            color: var(--thm-base);
            padding-right: 15px;
            cursor: pointer;
            text-decoration: underline;
        }

        .advanced-search__container {
            border: 1px solid #e5e5e5;
            padding-top: 15px;
            padding-bottom: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: left !important;
        }

        .btn-advanced-search {
            width: 7rem;
            padding: 11px 0;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* Select2 Customization */

        .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            padding: .375rem .75rem;
            height: calc(1.5em + .75rem + 2px) !important;
            border-radius: 0 !important;
        }


        .select2-selection__arrow {
            display: none;
        }

        .select2-selection__clear {
            font-size: 1.2rem;
            line-height: normal;
            color: var(--thm-base);
        }

        .select2-selection__rendered {
            padding-right: 0 !important;
        }

        .select2-search__field:focus {
            outline: none !important;
            border-color: var(--thm-base) !important;
            border-width: 2px !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--thm-base) !important;
        }
    </style>
@endsection


@section('content')
    <section class="page-header"
             style="background-image: url({{asset('frontend/images/banner/search-page-banner.jpg')}});">
        <div class="container">
            <h2>Search Philanthropists</h2><br>
            <h5 style="color: #fff;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce lacinia velit felis.
                Suspendisse eleifend consectetur mattis.</h5>
            <div class="search-form">
                <form action="#" class="search-popup__form" onsubmit="searchFormSubmitHandler(event)">
                    <input id="current-page" type="hidden" name="page" value="1">
                    <input id="search-param" type="text" name="search" placeholder="Philanthropists Name...."
                           oninput="searchParamChangedHandler()">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
                <a href="#" class="advanced-search__link" onclick="advancedSearchClickHandler(event)">Advanced
                    Search</a>
            </div>

            <!-- Advanced Search Container -->
            <div id="advanced-search" class="container advanced-search__container"
                 style="display: none; margin-top: 2rem">
                <div class="row">
                    <!-- Name -->
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="advanced-philanthropist-name" class="advanced-search__title">Philanthropist
                                Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="advanced-philanthropist-name"
                                   name="advanced-philanthropist-name"
                                   placeholder="Philanthropist Name..."
                                   value=""
                                   onkeydown="searchInputsKeydownHandler(event)"
                                   required
                            />
                        </div>
                    </div>
                    <!-- Birth Year -->
                    <div class="col-6 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="advanced-birth-year" class="advanced-search__title">Birth Year</label>
                            <select id="advanced-birth-year" class="form-control select2 year-select">
                                <option></option>
                                @for ($year = 1700; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}">{{$year}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <!-- Death Year -->
                    <div class="col-6 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="advanced-death-year" class="advanced-search__title">Death Year</label>
                            <select id="advanced-death-year" class="form-control select2 year-select">
                                <option></option>
                                @for ($year = 1700; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}">{{$year}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Industries -->
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="advanced-industry" class="advanced-search__title">Industry</label>
                            <select id="advanced-industry" class="form-control select2">
                                <option></option>
                                @foreach($industries as $industry)
                                    <option value="{{$industry->id}}">{{$industry->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Business Name -->
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="advanced-business-name" class="advanced-search__title">Business Name</label>
                            <input type="text"
                                   class="form-control"
                                   placeholder="Business Name..."
                                   value=""
                                   name="advanced-business-name"
                                   onkeydown="searchInputsKeydownHandler(event)"
                                   id="advanced-business-name"
                                   required
                            />
                        </div>
                    </div>

                    <!-- Institution Name -->
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="advanced-institution-name" class="advanced-search__title">Institution
                                Name</label>
                            <input type="text"
                                   class="form-control"
                                   placeholder="Institution Name..."
                                   value=""
                                   onkeydown="searchInputsKeydownHandler(event)"
                                   name="advanced-institution-name"
                                   id="advanced-institution-name"
                                   required
                            />
                        </div>
                    </div>

                    <!-- Advanced Search Button -->
                    <div class="col-12">
                        <button class="thm-btn btn-advanced-search" onclick="advancedSearchButtonClickHandler(event)">
                            SEARCH
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="philanthropists-col-view" class="philanthropists col-view">
        <div class="container">
            <div class="row search-page-top contact-one__form">
                <div class="col-md-8">
                    <div class="row" style="margin-bottom: 40px;">
                        <div class="contact-one__box">
                            <div class="contact-one__box-social">
                                <a id="redirect-list-view"
                                   href="{{route('frontend.philanthropists.index', ['view' => 'list'])}}"
                                   class="fas fa-bars" title="List View"></a>
                                <a id="redirect-column-view"
                                   href="{{route('frontend.philanthropists.index', ['view' => 'column'])}}"
                                   class="fas fa-th active" title="Column View"></a>
                                <a id="redirect-table-view"
                                   href="{{route('frontend.philanthropists.index', ['view' => 'table'])}}"
                                   class="fas fa-table" title="Table View"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <select id="sort-type" class="selectpicker" style="max-height: 45px !important;"
                            onchange="sortChangedHandler(event)">
                        <option value="#">Sort By</option>
                        <option value="name-asc">
                            Sort By Name - A-Z
                        </option>
                        <option value="name-desc">
                            Sort By Name - Z-A
                        </option>
                        <option value="date-of-birth-asc">
                            Date of Birth - ASC
                        </option>
                        <option value="date-of-birth-desc">
                            Date of Birth - DESC
                        </option>
                        <option value="date-of-death-asc">
                            Date of Death - ASC
                        </option>
                        <option value="date-of-death-desc">
                            Date of Death - DESC
                        </option>
                    </select>
                </div>
            </div>

            <div id="loader" class="lds-ripple mt-1" style="display: none; left: 50%">
                <div></div>
                <div></div>
            </div>

            <!-- Philanthropist Items -->
            <div id="philanthropists-items" class="row">
                {{--                Sample--}}
                {{--                <div class="col-sm-12 col-md-6 col-lg-4">--}}
                {{--                    <div class="blog-one__single philanthropists-item">--}}
                {{--                        <div class="blog-one__image philanthropists-image">--}}
                {{--                            <a href="/philanthropist-detail.html">--}}
                {{--                                <img src="{{asset('frontend/images/avatar/mujp-philanthropist-portrait.jpg')}}" alt="">--}}
                {{--                            </a>--}}
                {{--                        </div>--}}
                {{--                        <div class="blog-one__content philanthropists-content">--}}
                {{--                            <h3><a href="/philanthropist-detail.html">Paul Baerwald</a></h3>--}}
                {{--                            <strong>Education Industry</strong>--}}
                {{--                            <hr>--}}
                {{--                            <span class="date-of-birth"><i class="far fa-calendar"></i> Date of Birth: 09/27/1871</span><br>--}}
                {{--                            <span class="date-of-death"><i--}}
                {{--                                        class="far fa-calendar"></i>  Date of Death: 06/02/1961</span><br>--}}
                {{--                            <i class="fas fa-map-marker-alt"></i> Country of Birth: Belarus / Grodno Region <br>--}}
                {{--                            <hr>--}}
                {{--                            <a href="/philanthropist-detail.html" class="blog-one__link">View Detail</a>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <!-- Philanthropist Item -->--}}
            </div>
            <!-- Philanthropist Items -->

            <!-- Pagination -->
            <div class="text-center d-flex justify-content-center">
                <div id="pagination-links" class="post-pagination">
                    {{--                    {{$philanthropists->links('frontend.panels.pagination', ['searchParam' => $searchParam, 'sortParam' => $sortParam])}}--}}
                </div>
            </div>
            <!-- Pagination -->
            {{--            <div class="text-center d-flex justify-content-center">--}}
            {{--                <div class="post-pagination">--}}
            {{--                    <a href="#"><i class="fa fa-angle-left"></i></a>--}}
            {{--                    <a href="#">1</a>--}}
            {{--                    <a href="#">2</a>--}}
            {{--                    <a href="#">3</a>--}}
            {{--                    <a href="#"><i class="fa fa-angle-right"></i></a>--}}
            {{--                </div>--}}
            {{--            </div>--}}

        </div>
    </section>

    @include('frontend.panels.cta')

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        let currentPage = 0;
        const searchUrl = '{{route('frontend.philanthropists.search')}}';
        const favoriteAddUrl = '{{route('member.favorites.store', '@philanthropistId')}}'
        const favoriteRemoveUrl = '{{route('member.favorites.destroy_with_philanthropist', '@philanthropistId')}}'
        const philanthropistsUrl = '{{route('frontend.philanthropists.index')}}';
        let searchInterval = null;

        $(document).ready(() => {
            const url = new URL(location.href);
            if (Array.from(url.searchParams).length > 1) {
                syncControlsWithCurrentUrl(refreshSearchUrls);
            } else {
                loadPhilanthropists(true);
            }

            // if (url.searchParams.get('search') || url.searchParams.get('sort')) {
            //     $('#search-param').val(url.searchParams.get('search'));
            //     $('#sort-type').val(url.searchParams.get('sort') ? url.searchParams.get('sort') : '#').trigger('change');
            //     refreshSearchUrls();
            //     // window.history.pushState({
            //     //     "html": 'test',
            //     //     "pageTitle": "test"
            //     // }, "", philanthropistsUrl + '?view=column');
            // } else {
            //     loadPhilanthropists(1, true);
            // }
            $('#advanced-birth-year, #advanced-death-year, #advanced-industry').select2({
                allowClear: true,
                placeholder: 'Select',
            });
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

        const loadPhilanthropists = (disableScrolling = false) => {


            $('#loader').slideDown('fast');

            const sortType = $('#sort-type').val() !== '#' ? $('#sort-type').val() : null;
            const searchParam = $('#search-param').val() !== '' ? $('#search-param').val() : null;

            refreshSearchUrls(true);

            // setTimeout(() => {
            $.ajax({
                'url': searchUrl,
                'type': 'post',
                'cache': false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "view": 'column',
                    ...getSearchParams()
                },
                success: function (response) {
                    document.getElementById('philanthropists-col-view').scrollIntoView();

                    setTimeout(() => {
                        if (!disableScrolling) {
                            var offset = $('#philanthropists-col-view').offset();
                            offset.top -= 30;
                            scrollTo({...offset, behavior: 'smooth'});
                        }
                        $('#pagination-links').html(response.pagination_links);
                    }, 305);

                    $('#loader').slideUp('fast');
                    $('#philanthropists-items').html(null);
                    $('#philanthropists-items').append(response.element ? response.element : `<p>No philanthropists were found matching your search criteria.</p>`);
                    const newItems = $('.philanthropists-item[style*="display: none"]');
                    for (const item of newItems) {
                        $(item).slideDown();
                    }
                },
                error: function (err) {
                    console.log(err);
                },
            });
            // }, 2000);
        };

        const searchFormSubmitHandler = event => {
            event.preventDefault();
            $('#sort-type').val('#').trigger('change');
            // const lastCurrent = currentPage;
            // currentPage = 0;
            // $('#philanthropists-items').html('');
            // loadPhilanthropists();
        };

        const searchParamChangedHandler = event => {
            if (searchInterval) {
                clearTimeout(searchInterval);
            }
            searchInterval = setTimeout(() => {
                refreshSearchUrls();
            }, 500);
        }

        const searchInputsKeydownHandler = (event) => {
            if(event.keyCode == 13){
                $('#sort-type').prop('selectedIndex', 0).trigger('change');
            }
        }

        const sortChangedHandler = event => {
            $('#philanthropists-items').html('');
            $('#current-page').val(1);
            loadPhilanthropists();
        };

        const paginate = event => {
            event.preventDefault();
            const page = $(event.currentTarget).data('value');
            $('#current-page').val(page);
            loadPhilanthropists();
        };

        const favoriteToggleHandler = (event) => {
            const favoriteElement = $(event.target);
            if (favoriteElement.hasClass('fas')) {
                //unfavorite
                $.ajax({
                    'url': favoriteRemoveUrl.replace('@philanthropistId', favoriteElement.data('philanthropist-id')),
                    'type': 'delete',
                    'cache': false,
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        favoriteElement.removeClass('fas');
                        favoriteElement.addClass('far');
                        favoriteElement.removeClass('favorite-selected');
                        favoriteElement.attr('title', 'Add to Favorites');
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            } else if (favoriteElement.hasClass('far')) {
                //favorite
                $.ajax({
                    'url': favoriteAddUrl.replace('@philanthropistId', favoriteElement.data('philanthropist-id')),
                    'type': 'post',
                    'cache': 'false',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        favoriteElement.removeClass('far');
                        favoriteElement.addClass('fas');
                        favoriteElement.addClass('favorite-selected');
                        favoriteElement.attr('title', 'Remove from Favorites');
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            }
        };

        const refreshSearchUrls = (refreshHistory = false) => {
            // const sortType = $('#sort-type').val() !== '#' ? $('#sort-type').val() : null;
            // const searchParam = $('#search-param').val() !== '' ? $('#search-param').val() : null;
            let columnNavUrl = philanthropistsUrl + '?view=column';
            let listNavUrl = philanthropistsUrl + '?view=list';
            let tableNavUrl = philanthropistsUrl + '?view=table';

            const searchParams = getSearchParams();
            let urlParams = '';
            for (const [key, value] of Object.entries(searchParams)) {
                if (!value)
                    continue;
                urlParams += (`&${key}=${value}`);
            }

            columnNavUrl = columnNavUrl + urlParams;
            listNavUrl = listNavUrl + urlParams;
            tableNavUrl = tableNavUrl + urlParams;

            // if (sortType || searchParam) {
            //     const parameters = (searchParam ? `&search=${searchParam}` : '') + (sortType ? `&sort=${sortType}` : '');
            //     columnNavUrl = columnNavUrl + parameters;
            //     listNavUrl = listNavUrl + parameters;
            // }
            $('#redirect-column-view').attr('href', columnNavUrl);
            $('#redirect-list-view').attr('href', listNavUrl);
            $('#redirect-table-view').attr('href', tableNavUrl);
            if (refreshHistory) {
                window.history.pushState({
                    "html": 'test',
                    "pageTitle": "test"
                }, "", columnNavUrl);
            }
        }

        const getSearchParams = () => {
            if ($('#advanced-search').css('display') == 'none') {
                return {
                    'search': $('#search-param').val() !== '' ? $('#search-param').val() : null,
                    'page': $('#current-page').val(),
                    'sort': $('#sort-type').val() !== '#' ? $('#sort-type').val() : null,
                }
            }
            return {
                'search': $('#advanced-philanthropist-name').val() !== '' ? $('#advanced-philanthropist-name').val() : null,
                'birthYear': $('#advanced-birth-year').val() != '' ? $('#advanced-birth-year').val() : null,
                'deathYear': $('#advanced-death-year').val() != '' ? $('#advanced-death-year').val() : null,
                'industry': $('#advanced-industry').val() != '' ? $('#advanced-industry').val() : null,
                'businessName': $('#advanced-business-name').val() != '' ? $('#advanced-business-name').val() : null,
                'institutionName': $('#advanced-institution-name').val() != '' ? $('#advanced-institution-name').val() : null,
                'page': $('#current-page').val(),
                'sort': $('#sort-type').val() !== '#' ? $('#sort-type').val() : null,
            }
        }

        const advancedSearchClickHandler = (event) => {
            event.preventDefault();
            toggleSearchPanels();
        }

        const toggleSearchPanels = (event) => {
            $('.search-form').slideUp();
            $('#advanced-search').slideDown();
            $('#advanced-philanthropist-name').val($('#search-param').val());
        };

        const advancedSearchButtonClickHandler = (event) => {
            const searchParams = getSearchParams();
            $('#sort-type').prop('selectedIndex', 0).trigger('change');
        }

        const syncControlsWithCurrentUrl = (callback) => {
            const url = new URL(location.href);
            if (url.searchParams.get('search')) {
                $('#search-param').val(url.searchParams.get('search'));
            }

            if (url.searchParams.get('page')) {
                $('#current-page').val(url.searchParams.get('page'));
            }

            if (url.searchParams.has('birthYear') || url.searchParams.has('deathYear') ||
                url.searchParams.has('industry') || url.searchParams.has('businessName') || url.searchParams.has('institutionName')) {
                toggleSearchPanels();

                if (url.searchParams.get('birthYear')) {
                    $('#advanced-birth-year').val(url.searchParams.get('birthYear')).trigger('change');
                }
                if (url.searchParams.get('deathYear')) {
                    $('#advanced-death-year').val(url.searchParams.get('deathYear')).trigger('change');
                }
                if (url.searchParams.get('industry')) {
                    $('#advanced-industry').val(url.searchParams.get('industry')).trigger('change');
                }
                if (url.searchParams.get('businessName')) {
                    $('#advanced-business-name').val(url.searchParams.get('businessName'));
                }
                if (url.searchParams.get('institutionName')) {
                    $('#advanced-institution-name').val(url.searchParams.get('institutionName'));
                }
            }

            $('#sort-type').val(url.searchParams.get('sort') ? url.searchParams.get('sort') : '#');
            loadPhilanthropists();
            if (callback){
                callback();
            }
        }
    </script>
@endsection


