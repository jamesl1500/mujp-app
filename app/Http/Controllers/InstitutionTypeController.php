<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\InstitutionType;
use App\Models\PhilanthropistInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionTypeController extends Controller
{
    public $viewPrefix = 'admin.library.institution-types.';

    public function index()
    {
        $institutionTypes = InstitutionType::where('name', '!=', InstitutionType::emptyRecordName())->orderBy('name')->get();

        return view($this->viewPrefix . 'institution-types-index', [
            'institutionTypes' => $institutionTypes,
        ]);
    }

    public function update(Request $request, $id)
    {
        $institutionType_name = $request->input('input-edit-institutionType-name');
        $executed = InstitutionType::where('id', '=', $id)->update(['name' => $institutionType_name]);
        if($executed){
            return redirect()->back()->with('success', 'Institution type updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating institutionType!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'institutionType_name' => 'required|string|max:255|unique:institution_types,name'
        ]);

        $institutionType = InstitutionType::create([
            "name" => ucfirst($request->institutionType_name)
        ]);

        if ($institutionType) {
            return redirect()->back()->with('success', 'Institution type added successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while adding institutionType!');
    }

    public function destroy(InstitutionType $institutionType)
    {
        $philanthropistInstitutions = PhilanthropistInstitution::withTrashed()->where('institution_type_id', '=', $institutionType->id);
        if($philanthropistInstitutions->exists()){
            $emptyInstitutionType = InstitutionType::where('name', '=', InstitutionType::emptyRecordName())->first();
            if($emptyInstitutionType){
                $philanthropistInstitutions->update([
                    'institution_type_id' => $emptyInstitutionType->id
                ]);
            }
        }
        $institutionType = InstitutionType::where('id', '=', $institutionType->id)->delete();

        if ($institutionType) {

            return redirect()->back()->with('success', 'Institution type deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while deleting institutionType!');
        }
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $institutionType_name = $request->input('institutionType_name');
        if (!$institutionType_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $institutionType = InstitutionType::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($institutionType_name)));

        if($request->itemId){
            $institutionType = $institutionType->where('id', '!=', $request->itemId);
        }

        if ($institutionType->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = InstitutionType::where('name', 'like', $institutionType_name . '%')->where('name', '!=', InstitutionType::emptyRecordName())->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $institutionType_name);
            }

            $similarRecords2
                = InstitutionType::where('name', 'like', '%' . $institutionType_name . '%')->where('name', '!=', InstitutionType::emptyRecordName())->whereNotIn('id', $foundIds)->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $institutionType_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);

            $result["similarRecords"] = htmlspecialchars_decode(view(
                $this->viewPrefix . 'institution-types-search-result',
                ['institutionTypes' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
