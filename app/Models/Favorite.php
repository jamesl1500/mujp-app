<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'philanthropist_id'
    ];

    public function user() {
        return $this->belongsTo(Users::class);
    }

    public function philanthropist() {
        return $this->belongsTo(Philanthropist::class);
    }
}
