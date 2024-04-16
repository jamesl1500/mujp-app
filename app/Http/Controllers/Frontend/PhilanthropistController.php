<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\File;
use App\Models\Industry;
use App\Models\Philanthropist;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\Models\Country;
use App\Models\City;

class PhilanthropistController extends Controller

{
    public function index(Request $request)
    {
        $industries = Industry::where('name', '!=', Industry::emptyRecordName())->orderBy('name')->get();
        if ($request->view == 'column') {
            return view('frontend.philanthropists.philanthropists_col_view', [
                'countries'  => Country::orderBy('name')->get(),
                'cities'     => City::orderBy('name')->get(),
                'industries' => $industries
            ]);
        }
        if ($request->view == 'table') {
            return view('frontend.philanthropists.philanthropists_table_view', [
                'countries'  => Country::orderBy('name')->get(),
                'cities'     => City::orderBy('name')->get(),
                'industries' => $industries
            ]);
        } else { //list view
            return view('frontend.philanthropists.philanthropists_list_view', [
                'countries'  => Country::orderBy('name')->get(),
                'cities'     => City::orderBy('name')->get(),
                'industries' => $industries
            ]);
        }
    }

    public function search(Request $request)
    {
        $searchParam = $request->search;

        $fname  = $request->fname;
        $lname  = $request->lname;
        $name = $fname . ' ' . $lname;

        $sortParam = $request->sort;

        $itemsPerPage = 6;

        $philanthropists = Philanthropist::query()->with([
            'business.industry',
            'city.state.country',
            'profileImage',
            'isFavoriteForUser'
        ])->where('status', '=', 'active');

        $orderQuery = "concat(firstname, ' ', lastname) ASC";
        switch ($sortParam) {
            case 'name-asc':
            case null:
                $orderQuery = "concat(firstname, ' ', lastname) ASC";
                break;
            case 'name-desc':
                $orderQuery = "concat(firstname, ' ', lastname) DESC";
                break;
                
            case 'date-of-birth-asc':
                $orderQuery = "year_of_birth ASC, month_of_birth ASC, date_of_birth ASC";
                break;
            case 'date-of-birth-desc':
                $orderQuery = "year_of_birth DESC, month_of_birth DESC, date_of_birth DESC";
                break;
            case 'date-of-death-asc':
                $orderQuery = "year_of_death ASC, month_of_death ASC, date_of_death ASC";
                break;
            case 'date-of-death-desc':
                $orderQuery = "year_of_death DESC, month_of_death DESC, date_of_death DESC";
                break;
        }

        if ($fname or $lname) {
            $philanthropists = $philanthropists->whereRaw("(concat(firstname, ' ', lastname) LIKE '" . $name . "%' OR concat(firstname, ' ', lastname) LIKE '%" . $name . "' OR concat(firstname, ' ', lastname) LIKE '%" . $name . "%')");
            if ($sortParam !== 'name-asc' && $sortParam !== 'name-desc') {
                $orderQuery = "CASE
                WHEN concat(firstname, ' ', lastname) LIKE '" . $name . "%' THEN 1
                WHEN concat(firstname, ' ', lastname) LIKE '%" . $name . "%' THEN 2
                ELSE 3
                END, " . $orderQuery;
            }
        }

        if ($request->birthYear) {
            $philanthropists = $philanthropists->where('year_of_birth', '=', $request->birthYear);
        }

        if ($request->deathYear) {
            $philanthropists = $philanthropists->where('year_of_death', '=', $request->deathYear);
        }

        if($request->cityBornIn){
            $philanthropists = $philanthropists->where('city_of_birth', '=', $request->cityBornIn);
        }



        if ($request->industry) {
            $philanthropistsIdList = Business::where('industry_id', '=', $request->industry)->get()->pluck('philanthropist_id');
            $philanthropists = $philanthropists->whereIn('id', $philanthropistsIdList);
        }

        if ($request->businessName) {
            $philanthropistsIdList = Business::where('name', 'like', '%' . $request->businessName . '%')->get()->pluck('philanthropist_id');
            $philanthropists = $philanthropists->whereIn('id', $philanthropistsIdList);
        }

        if ($request->institutionName) {
            $query = "SELECT DISTINCT philanthropist_id FROM philanthropist_institutions
LEFT JOIN institutions on philanthropist_institutions.institution_id = institutions.id
WHERE institutions.name LIKE '%" . $request->institutionName . "%' OR philanthropist_institutions.institution_other LIKE '%" . $request->institutionName . "%'";
            $philanthropistsIdList = Arr::pluck(DB::select(DB::raw($query)), 'philanthropist_id');
            $philanthropists = $philanthropists->whereIn('id', $philanthropistsIdList);
        }

        $philanthropists = $philanthropists->orderByRaw($orderQuery);

        if ($request->view == 'column') {
            $foundedPhilanthropistCount = $philanthropists->count();
            $philanthropists = $philanthropists->paginate($itemsPerPage);
            if ($request->ajax()) {
                return [
                    'element' => view('frontend.philanthropists.philanthropists_col_item', [
                        'philanthropists' => $philanthropists,
                    ])->render(),
                    'pagination_links' => $philanthropists->links('frontend.panels.pagination_ajax', ['searchParam' => $searchParam, 'sortParam' => $sortParam])->toHtml(),
                    'current_page' => $philanthropists->currentPage(),
                    'last_page' => $philanthropists->lastPage(),
                    'total' => $philanthropists->total(),
                    'next_page_url' => $philanthropists->nextPageUrl()
                ];
            }
        }
        if ($request->view == 'table') {
            $itemsPerPage = 20;
            $foundedPhilanthropistCount = $philanthropists->count();
            $philanthropists = $philanthropists->paginate($itemsPerPage);
            if ($request->ajax()) {
                return [
                    'element' => view('frontend.philanthropists.philanthropists_table_item', [
                        'philanthropists' => $philanthropists,
                    ])->render(),
                    'pagination_links' => $philanthropists->links('frontend.panels.pagination_ajax', ['searchParam' => $searchParam, 'sortParam' => $sortParam])->toHtml(),
                    'current_page' => $philanthropists->currentPage(),
                    'last_page' => $philanthropists->lastPage(),
                    'total' => $philanthropists->total(),
                    'next_page_url' => $philanthropists->nextPageUrl()
                ];
            }
        } else { //list view
            $itemsPerPage = 9;
            if ($request->refreshPageSize) {
                $itemsPerPage = $itemsPerPage * $request->refreshPageSize;
            }
            $philanthropists = $philanthropists->paginate($itemsPerPage);
            if ($request->ajax()) {
                return [
                    'element' => view('frontend.philanthropists.philanthropists_list_item', [
                        'philanthropists' => $philanthropists
                    ])->render(),
                    'isLastPage' => $philanthropists->lastPage() == $philanthropists->currentPage()
                ];
            }
        }
    }

    public function show($id)
    {
        $philanthropist = Philanthropist::with([
            'business.industry',
            'city',
            'familyMembers.relatedPhilanthropist',
            'familyMembers.relationType',
            'countryOfMostLivedIn',
            'stateOfMostLivedIn',
            'cityOfMostLivedIn',
            'profileImage',
            'galleryImages',
            'articleFiles',
            'associatedPeoples.associatedPhilanthropist',
            'institutions.institution',
            'institutions.role',
            'institutions.type',
            'institutions.city',
        ])->where('status', '=', 'active')->find($id);
        if (!$philanthropist) {
            return redirect(route('frontend.philanthropists.index'));
        }
        return view('frontend.philanthropists.philanthropist_detail', [
            'philanthropist' => $philanthropist
        ]);
    }

    public function showWithSlug($slug, $id)
    {
        $philanthropist = Philanthropist::with([
            'business.industry',
            'city',
            'familyMembers.relatedPhilanthropist',
            'familyMembers.relationType',
            'countryOfMostLivedIn',
            'stateOfMostLivedIn',
            'cityOfMostLivedIn',
            'profileImage',
            'galleryImages',
            'articleFiles',
            'associatedPeoples.associatedPhilanthropist',
            'institutions.institution',
            'institutions.role',
            'institutions.type',
            'institutions.city',
        ])->where('status', '=', 'active')->find($id);
        if (!$philanthropist) {
            return redirect(route('frontend.philanthropists.index'));
        }
        return view('frontend.philanthropists.philanthropist_detail', [
            'philanthropist' => $philanthropist
        ]);
    }
}
