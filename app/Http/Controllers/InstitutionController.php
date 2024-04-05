<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Institution;
use App\Models\PhilanthropistInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionController extends Controller
{
    public $viewPrefix = 'admin.library.institutions.';

    public function index()
    {
        $institutions = Institution::orderBy('name')->get();

        return view($this->viewPrefix . 'institutions-index', [
            'institutions' => $institutions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $institution_name = $request->input('input-edit-institution-name');
        $executed = Institution::where('id', '=', $id)->update(['name' => $institution_name]);
        if($executed){
            return redirect()->back()->with('success', 'Institution updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating institution!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255|unique:institutions,name'
        ]);

        $institution = Institution::create([
            "name" => ucfirst($request->institution_name)
        ]);

        if ($institution) {
            return redirect()->back()->with('success', 'Institution added successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while adding institution!');
    }

    public function destroy(Institution $institution)
    {
        $institutionId = $institution->id;
        $institution = Institution::where('id', '=', $institution->id)->delete();

        $philanthropistInstitutions = PhilanthropistInstitution::withTrashed()->where('institution_id', '=',$institutionId);
        if($philanthropistInstitutions){
            $philanthropistInstitutions->delete();
        }

        if ($institution) {
            return redirect()->back()->with('success', 'Institution deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while deleting institution!');
        }
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $institution_name = $request->input('institution_name');
        if (!$institution_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $institution = Institution::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($institution_name)));

        if($request->itemId){
            $institution = $institution->where('id', '!=', $request->itemId);
        }

        if ($institution->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = Institution::where('name', 'like', $institution_name . '%')->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $institution_name);
            }

            $similarRecords2
                = Institution::where('name', 'like', '%' . $institution_name . '%')->whereNotIn('id', $foundIds)->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $institution_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);

            $result["similarRecords"] = htmlspecialchars_decode(view(
                $this->viewPrefix . 'institutions-search-result',
                ['institutions' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
