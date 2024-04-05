<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public static function emptyRecordName(){
        return '[No Relationship Type]';
    }
}
