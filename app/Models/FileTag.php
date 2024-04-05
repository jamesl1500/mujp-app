<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Session;

class FileTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public static function emptyRecordName(){
        return 'untagged';
    }

    public static function untaggedRecord() {
        return FileTag::where('name', '=', FileTag::emptyRecordName())->first();
    }

    public static function untaggedRecordId() {
        return FileTag::where('name', '=', FileTag::emptyRecordName())->first()->id;
    }

    public static function profileImageRecordId(){
        $recordId = Session::get('profileImageRecordId');
        if ($recordId){
           return $recordId;
        }
        $recordId = FileTag::where('name', '=', 'profile-image')->first()->id;
        Session::put('profileImageRecordId', $recordId);
        return $recordId;
    }

    public static function galleryImageRecordId(){
        $record = FileTag::where('name', '=', 'gallery-image')->first();
        if ($record){
            return $record->id;
        }
        return -1;
    }

    public static function articleFileRecordId(){
        $record = FileTag::where('name', '=', 'article')->first();
        if ($record){
            return $record->id;
        }
        return -1;
    }
}
