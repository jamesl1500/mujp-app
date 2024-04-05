<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function institutionType()
    {
        return $this->belongsTo(InstitutionType::class, 'institution_type_id', 'id');
    }

    public function institutionRole()
    {
        return $this->belongsTo(InstitutionRole::class, 'institution_role_id', 'id');
    }
}
