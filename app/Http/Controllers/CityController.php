<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Philanthropist;
use App\Models\PhilanthropistInstitution;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\Helpers\db_record_exists;
use function App\Helpers\find_and_place_mark_element;
use function App\Helpers\sql_trim_lower;
use function App\Helpers\str_trim_lower;

class CityController extends Controller
{

    public function index()
    {
        $countries = Country::all();
        $breadcrumbs = [
            ['link' => route('cities.index'), 'name' => "Cities"], ['name' => "View"]
        ];
        return view('admin.location.cities.cities-index', [
            'countries' => $countries,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function getCityTableRows(Request $request)
    {
        $result = ['cityTableRows' => null];
        if (!$request->stateId) {
            return response()->json($result);
        }

        $cities = City::where('state_id', '=', $request->stateId)->get();
        $result['cityTableRows'] = view('admin.location.cities.cities-search-result', [
            'cities' => $cities
        ])->render();
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('cities.index'), 'name' => "Cities"], ['name' => "Add"]
        ];

        $countries = Country::all();
        return view('admin.location.cities.cities-add', [
            'countries' => $countries,
            'selectedCountryId' => $request->data_country,
            'currentStateId' => $request->data_state,
            'breadcrumbs' => $breadcrumbs
        ]);

    }

    public function search(Request $request)
    {
        $request->validate([
            'column' => 'required|string',
            'table' => 'required|string',
        ]);

        $result = [
            'exists' => null,
            'similarRecords' => null,
            'elementId' => $request->elementId,
            'submitButtonId' => $request->submitButtonId,
            'includeColumnRequiredError' => null
        ];

        if (!$request->value) {
            return response()->json($result);
        }

        if (($request->includeColumn && !$request->includeValue)) {
            $result['includeColumnRequiredError'] = true;
            return response()->json($result);
        }

        $result['exists'] = db_record_exists($request->table, $request->column, $request->value, ['column' => $request->includeColumn, 'value' => $request->includeValue], $request->excludeOwnId ? $request->recordId : null);
        $similarRecords = City::where($request->column, 'like', $request->value . '%');
        if ($request->includeColumn) {
            $similarRecords = $similarRecords->where($request->includeColumn, '=', $request->includeValue);
        }
        $similarRecords = $similarRecords->orderBy($request->column)->get();
        $foundIds = [];
        foreach ($similarRecords as $record) {
            $foundIds[] = $record->id;
            $record[$request->column] = find_and_place_mark_element($record[$request->column], $request->value);
        }

        $similarRecordsOther = City::where($request->column, 'like', '%' . $request->value . '%')->whereNotIn('id', $foundIds);
        if ($request->includeColumn) {
            $similarRecordsOther = $similarRecordsOther->where($request->includeColumn, '=', $request->includeValue);
        }
        $similarRecordsOther = $similarRecordsOther->orderBy($request->column)->get();

        foreach ($similarRecordsOther as $record) {
            $foundIds[] = $record->id;
            $record[$request->column] = find_and_place_mark_element($record[$request->column], $request->value);
        }

        $similarRecords = $similarRecords->merge($similarRecordsOther);
        if ($request->similarRecordsLimit) {
            $similarRecords = $similarRecords->take($request->similarRecordsLimit);
        }

        $result['similarRecords'] = htmlspecialchars_decode(
            view('shared.similar-records', [
                'similarRecords' => $similarRecords,
                'column' => $request->column
            ])->render()
        );

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_country' => 'required|exists:countries,id',
            'data_state' => 'required|exists:states,id',
            'data_name' => 'required',
        ]);

        $city = City::where('state_id', '=', $request->data_state)->where(DB::raw(sql_trim_lower('name')), '=', str_trim_lower($request->data_name))->get();
        if ($city->count()) {
            return redirect()->back()->with('error', 'City Name is already exists!');
        }

        $country = Country::where('id', '=', $request->data_country)->get()->first();
        $state = State::where('id', '=', $request->data_state)->get()->first();

        $values = [
            'name' => $request->data_name,
            'state_id' => $state->id,
            'state_code' => $state->iso2,
            'country_id' => $country->id,
            'country_code' => $country->iso2,
            'latitude' => $request->data_latitude,
            'longitude' => $request->data_longitude,
            'flag' => 1
        ];

        $city = City::create($values);
        if ($city) {
            return redirect()->back()->with('success', 'City created successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while creating the city!');
        }
    }

    public function show(City $city)
    {
        //
    }

    public function edit(City $city)
    {
        $breadcrumbs = [
            ['link' => route('cities.index'), 'name' => "Cities"], ['name' => "Edit"]
        ];


        $countries = Country::all();
        return view('admin.location.cities.cities-edit', [
            'countries' => $countries,
            'city' => $city,
            'breadcrumbs' => $breadcrumbs
        ]);

    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'data_country' => 'required|exists:countries,id',
            'data_state' => 'required|exists:states,id',
            'data_name' => 'required'
        ]);

        $existingCity = City::where(DB::raw(sql_trim_lower('name')), '=', str_trim_lower($request->data_name))
            ->where('id', '!=', $city->id)
            ->where('state_id', '=', $city->state_id);

        if ($existingCity->count()) {
            return redirect()->back()->with('error', 'City name is already exists!');
        }

        $state = State::where('id', '=', $request->data_state)->get()->first();
        $country = Country::where('id', '=', $request->data_country)->get()->first();

        $values = [
            'name' => $request->data_name,
            'state_id' => $state->id,
            'state_code' => $state->iso2,
            'country_id' => $country->id,
            'country_code' => $country->iso2,
            'latitude' => $request->data_latitude,
            'longitude' => $request->data_longitude,
            'flag' => $city->flag
        ];

        $executed = City::where('id', '=', $city->id)->update($values);
        if ($executed) {
            return redirect()->back()->with('success', 'City updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while updating the city!');
        }

//  "_method" => "PUT"
//  "_token" => "2vUSxSvKQQkkWJdlwt1LBhgjpcHRY3y9V91l7T3g"
//  "data_country" => "225"
//  "data_state" => "2182"
//  "data_name" => "Fatihli"
//  "data_latitude" => "23.00000000"
//  "data_longitude" => "12.00000000"
        dd($request->all());
    }

    public function destroy(City $city)
    {
        $philanthropist_records = Philanthropist::withTrashed()->where('city_of_birth', '=', $city->id);
        if($philanthropist_records->exists()){
            return redirect()->back()
                ->withInput([
                    'data_country' => $city->country_id,
                    'data_state' => $city->state_id
                ])
                ->with('error', "There is a philanthropist attached to this city. Please change philanthropist's city of birth first!");
        }
        $philanthropistInstitutions = PhilanthropistInstitution::withTrashed()->where('city_id', '=', $city->id);
        if($philanthropistInstitutions->exists()){
            return redirect()->back()
                ->withInput([
                    'data_country' => $city->country_id,
                    'data_state' => $city->state_id
                ])
                ->with('error', "There is a institution attached to this city. Please change city of the philanthropist's institution!");
        }
        $executed = City::where('id', '=', $city->id)->delete();
        if ($executed) {
            return redirect()->back()
                ->withInput([
                    'data_country' => $city->country_id,
                    'data_state' => $city->state_id
                ])
                ->with('success', 'City deleted successfully.');
        } else {
            return redirect()->back()
                ->withInput([
                    'data_country' => $city->country_id,
                    'data_state' => $city->state_id
                ])
                ->with('error', 'Something went wrong while deleting the city!');
        }
    }
}
