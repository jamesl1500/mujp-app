<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\InstitutionRole;
use App\Models\PhilanthropistInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionRoleController extends Controller
{
    public $viewPrefix = 'admin.library.institution-roles.';

    public function index()
    {
        $institutionRoles = InstitutionRole::where('name', '!=', InstitutionRole::emptyRecordName())->orderBy('name')->get();

        return view($this->viewPrefix . 'institution-roles-index', [
            'institutionRoles' => $institutionRoles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $institutionRole_name = $request->input('input-edit-institutionRole-name');
        $executed = InstitutionRole::where('id', '=', $id)->update(['name' => $institutionRole_name]);
        if($executed){
            return redirect()->back()->with('success', 'Institution role updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating institutionRole!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'institutionRole_name' => 'required|string|max:255|unique:institution_roles,name'
        ]);

        $institutionRole = InstitutionRole::create([
            "name" => ucfirst($request->institutionRole_name)
        ]);

        if ($institutionRole) {
            return redirect()->back()->with('success', 'Institution type added successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while adding institutionRole!');
    }

    public function destroy(InstitutionRole $institutionRole)
    {
        $philanthropistInstitutions = PhilanthropistInstitution::withTrashed()->where('institution_role_id', '=', $institutionRole->id);
        if($philanthropistInstitutions->exists()){
            $emptyInstitutioRole = InstitutionRole::where('name', '=', InstitutionRole::emptyRecordName())->first();
            if($emptyInstitutioRole){
                $philanthropistInstitutions->update([
                    'institution_role_id' => $emptyInstitutioRole->id
                ]);
            }
        }
        $institutionRole = InstitutionRole::where('id', '=', $institutionRole->id)->delete();

        if ($institutionRole) {
            return redirect()->back()->with('success', 'Institution type deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while deleting institutionRole!');
        }
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $institutionRole_name = $request->input('institutionRole_name');
        if (!$institutionRole_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $institutionRole = InstitutionRole::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($institutionRole_name)));

        if($request->itemId){
            $institutionRole = $institutionRole->where('id', '!=', $request->itemId);
        }

        if ($institutionRole->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = InstitutionRole::where('name', 'like', $institutionRole_name . '%')->where('name', '!=', InstitutionRole::emptyRecordName())->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $institutionRole_name);
            }

            $similarRecords2
                = InstitutionRole::where('name', 'like', '%' . $institutionRole_name . '%')->where('name', '!=', InstitutionRole::emptyRecordName())->whereNotIn('id', $foundIds)->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $institutionRole_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);

            $result["similarRecords"] = htmlspecialchars_decode(view(
                $this->viewPrefix . 'institution-roles-search-result',
                ['institutionRoles' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
