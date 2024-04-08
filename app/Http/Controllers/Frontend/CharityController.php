<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CharityController extends Controller
{
    //
    public function charity()
    {
        return view('frontend.charity');
    }
}
