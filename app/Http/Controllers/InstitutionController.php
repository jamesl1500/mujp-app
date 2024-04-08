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
        $institution_city = $request->input('input-edit-institution-city');
        $institution_state = $request->input('input-edit-institution-state');
        $institution_notes = $request->input('input-edit-institution-notes');
        $institution_country = $request->input('input-edit-institution-country');

        $executed = Institution::where('id', '=', $id)->update(['name' => $institution_name, 'city' => $institution_city, 'state' => $institution_state, 'notes' => $institution_notes, 'country' => $institution_country]);
        if($executed){
            return redirect()->back()->with('success', 'Institution updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating institution!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255|unique:institutions,name',
            'input-add-institution-city' => 'nullable|string|max:255',
            'input-add-institution-state' => 'nullable|string|max:255',
            'input-add-institution-notes' => 'nullable|string|max:255',
            'input-add-institution-country' => 'nullable|string|max:255',
        ]);

        $institution = Institution::create([
            "name" => ucfirst($request->institution_name),
            "city" => ucfirst($request->input('input-add-institution-city')),
            "state" => ucfirst($request->input('input-add-institution-state')),
            "notes" => ucfirst($request->input('input-add-institution-notes')),
            "country" => ucfirst($request->input('input-add-institution-country')),
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
