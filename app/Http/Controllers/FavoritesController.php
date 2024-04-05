<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Philanthropist;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoritesController extends Controller
{
    public $bladePrefix = 'member.favorites.';

    public function index()
    {
        return view($this->bladePrefix . 'favorites-index');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id != Auth::user()->id) {
            return redirect()->back()->with('error', 'Favorite cannot be deleted because it is not yours.');
        }

        $executed = Favorite::where('id', '=', $favorite->id)->delete();
        if ($executed) {
            return redirect()->back()->with('success', 'Favorite deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while deleting favorite.');
    }

    public function store(Philanthropist $philanthropist, Request $request)
    {
        if ($request->ajax() && Auth::id()) {
            $isAlreadyFavorited = Favorite::where('user_id', '=', Auth::id())
                ->where('philanthropist_id', '=', $philanthropist->id)->exists();
            if ($isAlreadyFavorited) {
                return response()->json('ok');
            }
            $created = Favorite::create([
                'user_id' => Auth::id(),
                'philanthropist_id' => $philanthropist->id
            ]);
            if ($created) {
                return response()->json('ok');
            } else {
                Log::error('Favorite is not added. UserId: ' . Auth::id() . ', PhilanthropistId: ' . $philanthropist->id);
                return response()->json('Record is not created.', 500);
            }
        }
        return response()->json('authentication_fail');
    }

    public function destroyWithPhilanthropist(Philanthropist $philanthropist)
    {
        $favorite = Favorite::where('philanthropist_id', '=', $philanthropist->id)
            ->where('user_id', '=', Auth::id());
        if ($favorite->exists()){
            $executed = $favorite->delete();
            if ($executed) {
                return response()->json('ok');
            }
            Log::error('Favorite is not removed. UserId: ' . Auth::id() . ', PhilanthropistId: ' . $philanthropist->id);
            return response()->json('Record is not removed.', 500);
        }
        return response()->json('Record is not exists.', 500);
    }
}
