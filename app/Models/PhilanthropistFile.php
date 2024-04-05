<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhilanthropistFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'philanthropist_id',
        'file_id'
    ];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function philanthropist()
    {
        return $this->belongsTo(Philanthropist::class, 'philathropist_id', 'id');
    }

    public function deleteWithFile()
    {
        return $this->file()->delete() && $this->delete();
    }
}
