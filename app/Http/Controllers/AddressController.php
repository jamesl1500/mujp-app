<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getStates(Request $request)
    {
        $states = State::where('country_id', '=', $request->input('countryId'))->orderBy('name')->get();
        return view('shared.option-list', ['options' => $states, 'addEmptyOption' => true])->render();
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', '=', $request->input('stateId'))->orderBy('name')->get();
        return view('shared.option-list', ['options' => $cities, 'addEmptyOption' => true])->render();
    }
}
