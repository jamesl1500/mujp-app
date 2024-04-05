<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\PhilanthropistRelation;
use App\Models\RelationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelationTypeController extends Controller
{
    public $viewPrefix = 'admin.library.relation-types.';

    public function index()
    {
        $relationTypes = RelationType::where('name', '!=', RelationType::emptyRecordName())->orderBy('name')->get();

        return view($this->viewPrefix . 'relation-types-index', [
            'relationTypes' => $relationTypes,
        ]);
    }

    public function update(Request $request, $id)
    {
        $relationType_name = $request->input('input-edit-relationType-name');
        $executed = RelationType::where('id', '=', $id)->update(['name' => $relationType_name]);
        if($executed){
            return redirect()->back()->with('success', 'Relationship type updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating relationType!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'relationType_name' => 'required|string|max:255|unique:relation_types,name'
        ]);

        $relationType = RelationType::create([
            "name" => ucfirst($request->relationType_name)
        ]);

        if ($relationType) {
            return redirect()->back()->with('success', 'Relationship type added successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while adding relationType!');
    }

    public function destroy(RelationType $relationType)
    {
        $philanthropistRelations = PhilanthropistRelation::withTrashed()->where('relation_type_id', '=', $relationType->id);
        if($philanthropistRelations->exists()){
            $emptyRelationType = RelationType::where('name', '=', RelationType::emptyRecordName())->first();
            if($emptyRelationType){
                $philanthropistRelations->update([
                    'relation_type_id' => $emptyRelationType->id
                ]);
            }
        }

        $relationType = RelationType::where('id', '=', $relationType->id)->delete();

        if ($relationType) {
            return redirect()->back()->with('success', 'Relationship type deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong while deleting relationType!');
        }
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $relationType_name = $request->input('relationType_name');
        if (!$relationType_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $relationType = RelationType::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($relationType_name)));

        if($request->itemId){
            $relationType = $relationType->where('id', '!=', $request->itemId);
        }

        if ($relationType->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = RelationType::where('name', 'like', $relationType_name . '%')->where('name', '!=', RelationType::emptyRecordName())->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $relationType_name);
            }

            $similarRecords2
                = RelationType::where('name', 'like', '%' . $relationType_name . '%')->where('name', '!=', RelationType::emptyRecordName())->whereNotIn('id', $foundIds)->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $relationType_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);

            $result["similarRecords"] = htmlspecialchars_decode(view(
                $this->viewPrefix . 'relation-types-search-result',
                ['relationTypes' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
