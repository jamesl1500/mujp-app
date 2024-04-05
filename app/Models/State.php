<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'latitude',
        'longitude',
        'country_code'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function cities(){
        return $this->hasMany(City::class,'state_id', 'id');
    }
}
