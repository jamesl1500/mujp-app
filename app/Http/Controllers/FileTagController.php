<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\File;
use App\Models\FileTag;
use App\Models\PhilanthropistFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\Helpers\str_trim_lower;

class FileTagController extends Controller
{
    public function index(Request $request)
    {
        $fileTags = FileTag::where('name', '!=', FileTag::emptyRecordName())->orderBy('name')->get();
        return view('admin.library.file-tags.file_tags_index', [
            'fileTags' => $fileTags,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'fileTag_name' => 'required|string|max:256|unique:file_tags,name',
            'fileTag_description' => 'max:256'
        ];

        $request->validate($rules);

        $created = FileTag::create([
            'name' => str_trim_lower($request->fileTag_name),
            'description' => ucfirst($request->fileTag_description)
        ]);

        if ($created) {
            return redirect()->back()->with('success', 'File tag created successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while creating file tag!');
    }

    public function update(Request $request, FileTag $fileTag)
    {
        $rules = [
            'input-edit-fileTag-name' => 'required|string|max:256|unique:file_tags,name,' . $fileTag->id,
            'input-edit-fileTag-description' => 'max:256'
        ];
        $request->validate($rules);

        $updated = $fileTag->update([
            'name' => str_trim_lower($request->input('input-edit-fileTag-name')),
            'description' => ucfirst($request->input('input-edit-fileTag-description'))
        ]);

        if ($updated) {
            return redirect()->back()->with('success', 'File tag updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while updating file tag!');
    }

    public function destroy(FileTag $fileTag)
    {
        $taggedFiles = File::where('file_tag_id', '=', $fileTag->id)->update([
            'file_tag_id' => FileTag::untaggedRecordId()
        ]);

        if ($fileTag->delete()) {
            return redirect()->back()->with('success', 'File tag deleted successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong while deleting file tag!');
    }

    public function search(Request $request)
    {
        $result = ['status' => 'empty'];

        $fileTag_name = $request->input('fileTag_name');
        if (!$fileTag_name) {
            $result['status'] = 'empty';
            return response()->json($result);
        }

        $fileTag = FileTag::where(DB::raw('ltrim(rtrim(lower(name)))'), '=', trim(strtolower($fileTag_name)));

        if ($request->itemId) {
            $fileTag = $fileTag->where('id', '!=', $request->itemId);
        }

        if ($fileTag->count()) {
            $result["status"] = "exists";
        }

        if (!is_null($request->input('searchSimilarRecords')) && $request->input('searchSimilarRecords') == true) {

            $similarRecords = FileTag::where('name', 'like', $fileTag_name . '%')->where('name', '!=', FileTag::emptyRecordName())->orderBy('name')->get();
            $foundIds = [];
            foreach ($similarRecords as $record) {
                $foundIds[] = $record->id;
                $record->name = Helper::findAndPlaceMarkElement($record->name, $fileTag_name);
            }

            $similarRecords2
                = FileTag::where('name', 'like', '%' . $fileTag_name . '%')->whereNotIn('id', $foundIds)->where('name', '!=', FileTag::emptyRecordName())->orderBy('name')->get();
            foreach ($similarRecords2 as $similarRecord) {
                $similarRecord->name = Helper::findAndPlaceMarkElement($similarRecord->name, $fileTag_name);
            }

            $similarRecords = $similarRecords->merge($similarRecords2);


            $result["similarRecords"] = htmlspecialchars_decode(view(
                'admin.library.file-tags.file_tags_search_result',
                ['fileTags' => $similarRecords]
            )->render());
        }

        return response()->json($result);
    }
}
