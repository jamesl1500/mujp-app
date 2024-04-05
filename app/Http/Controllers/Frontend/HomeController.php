<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Philanthropist;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $philanthropists = Philanthropist::with(['business', 'profileImage'])->inRandomOrder()->limit(10)->get();

        return view('frontend.home', [
            'philanthropists' => $philanthropists
        ]);
    }
}
