<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'philanthropist_id',
        'industry_id',
        'industry_other',
        'name',
        'details'
    ];

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class,'industry_id','id');
    }

    public function philanthropist(): BelongsTo
    {
        return $this->belongsTo(Philanthropist::class);
    }

}
