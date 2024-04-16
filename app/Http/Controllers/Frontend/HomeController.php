<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Philanthropist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $philanthropists = Philanthropist::with(['business', 'profileImage'])->inRandomOrder()->limit(10)->get();

        return view('frontend.home', [
            'philanthropists' => $philanthropists
        ]);
    }

    public function update(Request $request)
    {
        // Update the first record in the homepage_text table with the data from the request
        DB::table('homepage_text')->where('id', 1)->update([
            ''.$request->htid.'' => $request->content
        ]);

        /// Send back json
        return response()->json([
            'success' => true
        ]);
    }
}
