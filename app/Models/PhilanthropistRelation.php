<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhilanthropistRelation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'philanthropist_id',
        'related_philanthropist_id',
        'relation_type_id',
        'created_by',
        'firstname',
        'lastname'
    ];

    public function relatedPhilanthropist() {
        return $this->belongsTo(Philanthropist::class, 'related_philanthropist_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class,created_by, 'id');
    }

    public function relationType()
    {
        return $this->belongsTo(RelationType::class, 'relation_type_id', 'id');
    }
}
