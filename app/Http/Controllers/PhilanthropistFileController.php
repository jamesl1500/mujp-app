<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileTag;
use App\Models\Philanthropist;
use App\Models\PhilanthropistFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Events\FileUploaded;

class PhilanthropistFileController extends Controller
{
    public function uploadFile(Request $request, Philanthropist $philanthropist)
    {
        try {
            if (!is_null($request->file)) {
                $basePath = 'public/uploads/philanthropists/';
                $savePath = $basePath . $philanthropist->id;

                $file = $request->file('file');

                if (!Storage::exists($savePath)) {
                    Storage::makeDirectory($savePath);
                }

                //public/uploads/philanthropists/{philanthropistId}/{extension}
                $savePath = $savePath . '/' . $file->extension();
                if (!Storage::exists($savePath)) {
                    Storage::makeDirectory($savePath);
                }

                $originalFileName = $file->getClientOriginalName();
                $fileTag = $this->getFileTagByFileName($originalFileName, '_');

                if (strlen($originalFileName) > 25) {
                    $originalFileName = substr($originalFileName, 0, 25) . '.' . $file->getClientOriginalExtension();
                }
                //Store file to storage path
                $uploadedFileName = $request->file('file')->storeAs($savePath, $originalFileName);

                if ($uploadedFileName == 'false ' | $uploadedFileName == false) {
                    Log::warning('Error on storing file. (file system). PhilanthropistId: ' . $philanthropist->id . ', FileName: ' . $savePath . $originalFileName);
                    return redirect()->back()->with('error', 'Error on storing file. (file system)');
                }

                $existingFile = File::with('philanthropistFiles')->where('path', '=', $uploadedFileName);
                if ($existingFile->exists()) {
                    //Update file updated_at
                    $existingFile = $existingFile->first();
                    $executed = $existingFile->update([
                        'updated_at' => now(),
                        'updated_by' => auth()->id()
                    ]);
                    if (!$executed) {
                        Log::warning('File updated_at time is not updated properly. PhilanthropistId: ' . $philanthropist->id . ', FilePath: ' . $uploadedFileName);
                    }

                    //Check PhilanthropistFile exist. If exists update created_at time else create new pair
                    $philFile = PhilanthropistFile::where('file_id', '=', $existingFile->id)->where('philanthropist_id', '=', $philanthropist->id);
                    if ($philFile->exists()) {
                        $philFile->update([
                            'updated_at' => now()
                        ]);
                    } else {
                        $philFile = PhilanthropistFile::create([
                            'philanthropist_id' => $philanthropist->id,
                            'file_id' => $existingFile->id
                        ]);
                        if (!$philFile) {
                            Log::warning('PhilanthropistFile is not stored. PhilanthropistId: ' . $philanthropist->id . ', FilePath: ' . $uploadedFileName);
                            return redirect()->back()->with('error', 'PhilanthropistFile is not stored');
                        }
                    }
                } else {
                    $savingFile = File::create([
                        'file_tag_id' => $fileTag->id,
                        'created_by' => Auth::id(),
                        'path' => $uploadedFileName,
                        'name' => $originalFileName,
                        'extension' => $file->getClientOriginalExtension(),
                        'type' => $file->getMimeType()
                    ]);
                    if (!$savingFile) {
                        Log::warning('File informatin is not stored properly. PhilanthropistController@uploadFile, PhilanthropistId: ' .
                            $philanthropist->id . ', FilePath:' . $uploadedFileName);
                        return redirect()->back()->with('error', 'An error occurred when saving file information!');
                    }

                    $philanthropistFile = PhilanthropistFile::create([
                        'philanthropist_id' => $philanthropist->id,
                        'file_id' => $savingFile->id
                    ]);

                    if (!$philanthropistFile) {
                        return redirect()->back()->with('error', 'An error occurred when saving philanthropist file information!');
                    }
                }

                // After file upload logic
                event(new FileUploaded());
                return redirect()->back()->with('success', 'File uploaded successfully!');
            }

            // After file upload logic
            event(new FileUploaded());
            return redirect()->back()->with('success', 'File uploaded successfully!');

        } catch (\Exception $ex) {
            throw $ex;
            Log::error('An error occurred on PhilanthropistController->uploadFile() Ex: ' . $ex->getMessage());
            return response()->json('Error on file upload', 500);
        }
    }

    public function removeFile(Request $request, PhilanthropistFile $philanthropistFile)
    {
        try {
            $request->validate([
                'philanthropistId' => 'required'
            ]);

            if ($request->philanthropistId != $philanthropistFile->philanthropist_id) {
                Log::write('Philanthropist and file is not match. IP: ' . request()->ip());
                return response()->json('Philanthropist and file is not match!', 400);
            }

            if (!Storage::exists($philanthropistFile->file->path)) {
                return response()->json('File is not exist!', 400);
            }

            $filePath = $philanthropistFile->file->path;
            $deleted = Storage::delete($filePath);
            if (!$deleted) {
                return response()->json('File not deleted.', 500);
            }

            if (!$philanthropistFile->deleteWithFile()) {
                Log::warning('[PhilanthropistFileController]: File and PhilanthropistFile is not deleted from db. Path: ' . $filePath);
                return response()->json('File not deleted.', 500);
            }

            return response()->json('File deleted successfully!');
        } catch (\Exception $ex) {
            return response()->json('An error occured while deleting file.', 500);
        }
    }

    public function changeFileTag(Request $request, PhilanthropistFile $philanthropistFile)
    {
        $request->validate([
            'fileTagId' => 'required|exists:file_tags,id'
        ]);

        if ($request->fileTagId == FileTag::profileImageRecordId()) { //Change old profile image tag to untagged.
            $oldProfileImageRecords = DB::select('SELECT * FROM philanthropist_files PF INNER JOIN files ON PF.file_id = files.id WHERE PF.philanthropist_id = ' . $philanthropistFile->philanthropist_id . ' AND files.file_tag_id = ' . FileTag::profileImageRecordId());
            if ($oldProfileImageRecords) {
                foreach ($oldProfileImageRecords as $oldProfileImageRecord) {
                    DB::update('UPDATE files set file_tag_id = ' . FileTag::untaggedRecordId()
                        . ' WHERE id = ' . $oldProfileImageRecord->file_id);
                }
            }
        }

        $updated = $philanthropistFile->file()->update([
            'file_tag_id' => $request->fileTagId
        ]);

        if ($updated) {
            return response()->json([
                'message' => "FileTag changed successfully.",
                'tagName' => strtolower(FileTag::find($request->fileTagId)->name)
            ], 200);
        }
        return response()->json('Error on FileTag change request!', 500);
    }

    public function updateCaption(Request $request)
    {
        $request->validate([
            'philanthropistFileId' => 'required|exists:files,id',
            'caption' => 'required|string|max:255'
        ]);

        $updated = File::find($request->philanthropistFileId)->update([
            'caption' => $request->caption
        ]);

        if ($updated) {
            return response()->json('Caption updated successfully!', 200);
        }
        return response()->json('Error on caption update request!', 500);
    }

    public function getFileTagByFileName(string $fileName, string $seperator = '_')
    {
        if (!str_contains($fileName, $seperator)) {
            return FileTag::untaggedRecord();
        }

        $foundedIndex = strpos($fileName, $seperator);
        $possibleTag = ltrim(substr($fileName, '0', $foundedIndex));
        $fileTag = FileTag::where(DB::raw('lower(ltrim(rtrim(name)))'), '=', $possibleTag)->first();

        return $fileTag ? $fileTag : FileTag::untaggedRecord();
    }

}
