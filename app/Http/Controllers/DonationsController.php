<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonationsController extends Controller
{
    public $bladePrefix = 'member.donations.';

    public function index()
    {
        return view($this->bladePrefix . 'donations-index');
    }
}
