<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\Helpers\db_record_exists;
use function App\Helpers\find_and_place_mark_element;
use function App\Helpers\sql_trim_lower;

class StateController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        $breadcrumbs = [
            ['link' => route('states.index'), 'name' => "States"], ['name' => "View"]
        ];
        return view('admin.location.states.states-index', [
            'countries' => $countries,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function statesByCountry(Request $request)
    {
        $result = ['states' => null];
        if (!$request->countryId) {
            return response()->json($result);
        }

        $states = State::where('country_id', '=', $request->countryId)->get();
        $result['states'] = view('admin.location.states.states-search-result', [
            'states' => $states
        ])->render();
        return response()->json($result);
    }

    public function getStateOptions(Request $request) {
        $result = ['stateOptions' => null];
        if(!$request->countryId){
            return response()->json($result);
        }

        $states = State::where('country_id', '=', $request->input('countryId'))->orderBy('name')->get();
        $result['stateOptions'] = view('shared.option-list', ['options' => $states, 'addEmptyOption' => true, 'selectedValue' => $request->stateId])->render();
        return response()->json($result);
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
        $similarRecords = State::where($request->column, 'like', $request->value . '%');
        if ($request->includeColumn) {
            $similarRecords = $similarRecords->where($request->includeColumn, '=', $request->includeValue);
        }
        $similarRecords = $similarRecords->orderBy($request->column)->get();
        $foundIds = [];
        foreach ($similarRecords as $record) {
            $foundIds[] = $record->id;
            $record[$request->column] = find_and_place_mark_element($record[$request->column], $request->value);
        }

        $similarRecordsOther = State::where($request->column, 'like', '%' . $request->value . '%')->whereNotIn('id', $foundIds);
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

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('states.index'), 'name' => "States"], ['name' => "Add"]
        ];

        $countries = Country::all();
        return view('admin.location.states.states-add', [
            'countries' => $countries,
            'selectedCountryId' => $request->countryId,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:states',
            'countryId' => 'required|exists:countries,id'
        ]);

        $country = Country::where('id', '=', $request->countryId)->get()->first();

        $state = State::create([
            'name' => $request->name,
            'country_id' => $request->countryId,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'country_code' => $country->iso2
        ]);

        if ($state) {
            return redirect()->back()
                ->withInput(['countryId' => $request->countryId])
                ->with('success', 'State created successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while adding the state');
        }
    }

    public function show(State $state)
    {
        //
    }

    public function edit(State $state)
    {
        $breadcrumbs = [
            ['link' => route('states.index'), 'name' => "States"], ['name' => "Edit"]
        ];

        $countries = Country::all();
        return view('admin.location.states.states-edit', [
            'countries' => $countries,
            'state' => $state,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'countryId' => 'required|exists:countries,id'
        ]);

        $existingState = State::where(DB::raw(sql_trim_lower('name')), '=', $request->name)
            ->where('id', '!=', $state->id);
        if ($state->country_id != $request->countryId) {
            $existingState->where('country_id', '=', $request->countryId);
        }
        $existingState = $existingState->get();

        if ($existingState->count()) {
            return redirect()->back()->with('error', 'State name is already exists!');
        }

        $country = Country::where('id', '=', $request->countryId)->get()->first();
        $values = [
            'name' => ucfirst($request->name),
            'country_id' => $request->countryId,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'country_code' => $country->iso2
        ];

        $executed = State::where('id', '=', $state->id)->update($values);
        if ($executed) {
            return redirect()->back()
                ->withInput(['countryId' => $state->country_id])
                ->with('success', 'State updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while updating the state!');
        }
    }

    public function destroy(State $state)
    {
        $relatedCities = City::Where('state_id', '=', $state->id);
        if($relatedCities->exists()){
            return redirect()->back()->with('error', "There is a city attached to this state. Please change or delete state of the city!");
        }
        $executed = State::where('id', '=', $state->id)->delete();

        if ($executed) {
            return redirect()->back()
                ->withInput(['countryId' => $state->country_id])
                ->with('success', 'State deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while removing the state!');
        }
    }
}
