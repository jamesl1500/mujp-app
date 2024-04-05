<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhilanthropistInstitution extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'philanthropist_id',
        'institution_id',
        'institution_role_id',
        'institution_type_id',
        'city_id',
        'created_by',
        'institution_other'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    public function role(){
        return $this->belongsTo(InstitutionRole::class, 'institution_role_id','id');
    }

    public function type(){
        return $this->belongsTo(InstitutionType::class, 'institution_type_id','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
