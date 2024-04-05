<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhilanthropistAssociatedPeople extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'philanthropist_associated_peoples';

    protected $fillable = [
        'philanthropist_id',
        'associated_philanthropist_id',
        'created_by',
        'firstname',
        'lastname'
    ];

    public function philanthropist(){
        return $this->belongsTo(Philanthropist::class, 'philanthropist_id', 'id');
    }

    public function associatedPhilanthropist(){
        return $this->belongsTo(Philanthropist::class, 'associated_philanthropist_id', 'id');
    }
}
