<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Foundation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoundationController extends Controller
{
    public $viewPrefix = 'admin.library.foundations.';

    public function index()
    {
        $foundations = Foundation::orderBy('name')->get();

        return view($this->viewPrefix . 'foundations-index', [
            'foundations' => $foundations,
        ]);
    }

    public function update(Request $request, $id)
    {
        $foundation_name = $request->input('input-edit-foundation-name');
        $executed = Foundation::where('id', '=', $id)->update(['name' => $foundation_name]);
        if($executed){
            return redirect()->back()->with('success', 'Foundation updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating foundation!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foundation_name' => 'required|string|max:255|unique:foundations,name'
        ]);

        $foundation = Foundation::create([
            "name" => ucfirst($request->foundation_name)
        ]);

        if ($foundation) {
            return redirect()->back()->with('success', 'Foundation added successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while adding foundation!');
    }

    public function destroy(Foundation $foundation)
    {
        $foundation = Foundation::where('id', '=', $foundation->id)->delete();

        if ($foundation) {
            return redirect()->back()->with('success', 'Foundation deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while deleting foundation!');
        }
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $foundation_name = $request->input('foundation_name');
        if (!$foundation_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $foundation = Foundation::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($foundation_name)));

        if($request->itemId){
            $foundation = $foundation->where('id', '!=', $request->itemId);
        }

        if ($foundation->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = Foundation::where('name', 'like', $foundation_name . '%')->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $foundation_name);
            }

            $similarRecords2
                = Foundation::where('name', 'like', '%' . $foundation_name . '%')->whereNotIn('id', $foundIds)->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $foundation_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);

            $result["similarRecords"] = htmlspecialchars_decode(view(
                $this->viewPrefix . 'foundations-search-result',
                ['foundations' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
