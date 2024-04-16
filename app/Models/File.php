<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'file_tag_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'path',
        'name',
        'extension',
        'type',
        'caption',
        'updated_at'
    ];

    public function philanthropistFiles() {
        return $this->hasMany(PhilanthropistFile::class, 'file_id', 'id');
    }

    public function fileTag()
    {
        return $this->belongsTo(FileTag::class, 'file_tag_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::clas, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::clas, 'updated_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::clas, 'deleted_by', 'id');
    }
}
