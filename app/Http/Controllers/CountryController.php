<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\PhilanthropistInstitution;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        $breadcrumbs = [
            ['link' => route('countries.index'), 'name' => "Countries"], ['name' => "View"]
        ];
        return view('admin.location.countries.countries-index', [
            'countries' => $countries,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function create()
    {
        $regions = DB::table('countries')
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->get();

        $breadcrumbs = [
            ['link' => route('countries.index'), 'name' => "Countries"], ['name' => "Add"]
        ];

        return view('admin.location.countries.countries-add', [
            'regions' => $regions,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function search(Request $request)
    {
        $response = ['status' => 'empty'];
        $response['senderId'] = $request->senderId;
        if ($request->value && $request->fieldName) {
            if (trim($request->value) != '') {
                $record = DB::table('countries')->where(DB::raw('lower(ltrim(rtrim(' . $request->fieldName . ')))'), '=', $request->value);
                if ($request->recordId) {
                    $record = $record->where('id', '!=', $request->recordId);
                }
                $record = $record->get();
                if ($record->count()) {
                    $response['status'] = 'exists';
                } else {
                    $response['status'] = 'valid';
                }
            }
        }
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:countries',
            'iso2' => 'required|string|unique:countries'
        ]);

        $country = [
            'name' => ucfirst($request->name),
            'iso2' => strtoupper($request->iso2),
            'native' => ucfirst($request->native),
            'region' => $request->region,
            'emoji' => $request->emoji,
        ];

        $created = Country::create($country);
        if ($created) {
            return redirect()->back()->with('success', 'Country added successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while creating the country!');
        }
    }

    public function show(Country $country)
    {
        //
    }

    public function edit(Country $country, Request $request)
    {
        $regions = DB::table('countries')
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->get();

        $breadcrumbs = [
            ['link' => route('countries.index'), 'name' => "Countries"], ['name' => "Edit"]
        ];

        $country = Country::where('id', '=', $country->id)->get()->first();

        return view('admin.location.countries.countries-edit', [
            'regions' => $regions,
            'country' => $country,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|unique:countries,name,'.$country->id,
            'iso2' => 'required|string|unique:countries,iso2,'.$country->id
        ]);

        $executed = Country::where('id', '=', $country->id)->update([
            'name' => ucfirst($request->name),
            'native' => ucfirst($request->native),
            'region' => $request->region,
            'emoji' => $request->emoji,
            'iso2' => $request->iso2
        ]);

        if($executed) {
            return redirect()->back()
                ->withInput(['countryId' => $country->id])
                ->with('success', 'Country updated successfully.');
        }
        else {
            return redirect()->back()->with('error', 'Something went wrong while updating the country!');
        }
    }

    public function destroy(Country $country)
    {
        $relatedStates = State::where('country_id', '=', $country->id);
        if($relatedStates->exists()){
            return redirect()->back()->with('error', "There is a state attached to this Country. Please change or delete state's country!");
        }
        $relatedCities = City::where('country_id', '=', $country->id);
        if($relatedCities->exists()){
            return redirect()->back()->with('error', "There is a city attached to this Country. Please change or delete country of the state of the city!");
        }
        $executed = Country::where('id', '=', $country->id)->delete();

        if ($executed) {
            return redirect()->back()->with('success', 'Country deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while removing the country!');
        }
    }
}
