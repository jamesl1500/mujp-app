<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Business;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndustryController extends Controller
{
    public $viewPrefix = 'admin.library.industries.';

    public function index()
    {
        $industries = Industry::where('name', '!=', '[No Industry]')->orderBy('name')->get();

        return view($this->viewPrefix . 'industries-index', [
            'industries' => $industries,
        ]);
    }

    public function update(Request $request, $id)
    {
        $industry_name = $request->input('input-edit-industry-name');

        $executed = Industry::where('id', '=', $id)->update(['name' => $industry_name]);
        if ($executed) {
            return redirect()->back()->with('success', 'Industry updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating industry!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'industry_name' => 'required|string|max:255|unique:industries,name'
        ]);

        $industry = Industry::create([
            "name" => ucfirst($request->industry_name)
        ]);

        if ($industry) {
            return redirect()->back()->with('success', 'Industry added successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while adding industry!');
    }

    public function destroy(Industry $industry)
    {
        $industryId = $industry->id;
        $industry = Industry::where('id', '=', $industry->id)->delete();
        if ($industry) {
            $noIndustry = Industry::where('name', '=', '[No Industry]')->first();
            if($noIndustry){
                $updatingBusinesses =  Business::where('industry_id', '=', $industryId)->update([
                    'industry_id' => $noIndustry->id
                ]);
            }
            return redirect()->back()->with('success', 'Industry deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while deleted industry!');
        }
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $industry_name = $request->input('industry_name');
        if (!$industry_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $industry = Industry::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($industry_name)));

        if($request->itemId){
            $industry = $industry->where('id', '!=', $request->itemId);
        }

        if ($industry->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = Industry::where('name', 'like', $industry_name . '%')->where('name', '!=', '[No Industry]')->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $industry_name);
            }

            $similarRecords2
                = Industry::where('name', 'like', '%' . $industry_name . '%')->where('name', '!=', '[No Industry]')->whereNotIn('id', $foundIds)->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $industry_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);

            $result["similarRecords"] = htmlspecialchars_decode(view(
                $this->viewPrefix . 'industries-search-result',
                ['industries' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
