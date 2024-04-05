<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;
use function App\Helpers\custom_date_format;

class Philanthropist extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'status',
        'city_of_birth',
        'city_of_birth_other',
        'country_of_most_lived_in',
        'state_of_most_lived_in',
        'city_of_most_lived_in',
        'biography',
        'city_of_most_lived_in_other',
        'year_of_birth',
        'month_of_birth',
        'date_of_birth',
        'jewish_year_of_birth',
        'jewish_month_of_birth',
        'jewish_date_of_birth',
        'year_of_death',
        'month_of_death',
        'date_of_death',
        'jewish_year_of_death',
        'jewish_month_of_death',
        'jewish_date_of_death',
        'created_by',
        'deleted_at'
    ];

    public static function getPossibleStatuses()
    {
        $type = DB::select(DB::raw('SHOW COLUMNS FROM philanthropists WHERE Field = "status"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $values = array();
        foreach (explode(',', $matches[1]) as $value) {
            $values[] = trim($value, "'");
        }
        return $values;
    }

    public function business()
    {
        return $this->HasOne(Business::class, 'philanthropist_id', 'id');
    }

    public function institutions()
    {
        return $this->hasMany(PhilanthropistInstitution::class, 'philanthropist_id', 'id');
    }

    public function strInstitutions()
    {
        $result = '';
        foreach ($this->institutions()->get() as $philanthropistInstitution) {
            if ($philanthropistInstitution->institution_id && $philanthropistInstitution->institution()->first()) {
                $result = $result ? ($result . ', ' . $philanthropistInstitution->institution()->first()->name) : $philanthropistInstitution->institution()->first()->name;
            } else if ($philanthropistInstitution->institution_other) {
                $result = $result ? $result . ', ' . $philanthropistInstitution->institution_other : $philanthropistInstitution->institution_other;
            }
        }
        return $result == '' ? '-' : $result;

//        return $this->institutions()->exists() ? $this->institutions()->with('institution')->get()->implode('institution.name', ',') : '-';
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_of_birth', 'id');
    }

    public function countryOfMostLivedIn()
    {
        return $this->belongsTo(Country::class, 'country_of_most_lived_in', 'id');
    }

    public function stateOfMostLivedIn()
    {
        return $this->belongsTo(State::class, 'state_of_most_lived_in', 'id');
    }

    public function cityOfMostLivedIn()
    {
        return $this->belongsTo(City::class, 'city_of_most_lived_in', 'id');
    }

    public function relations()
    {
        return $this->hasMany(PhilanthropistRelation::class, 'philanthropist_id', 'id');
    }

    public function familyMembers()
    {
        return $this->hasMany(PhilanthropistRelation::class, 'philanthropist_id', 'id');
    }

    public function associatedPeoples()
    {
        return $this->hasMany(PhilanthropistAssociatedPeople::class, 'philanthropist_id', 'id');
    }

    public function industries()
    {
        return $this->hasManyThrough(Industry::class, Business::class, 'philanthropist_id', 'industry_id');
    }

    public function files()
    {
        return $this->hasManyThrough(File::class, PhilanthropistFile::class, 'philanthropist_id', 'id', 'id', 'file_id');
    }

    public function profileImage()
    {
        return $this->hasManyThrough(File::class, PhilanthropistFile::class, 'philanthropist_id', 'id', 'id', 'file_id')
            ->where('files.file_tag_id', '=', FileTag::profileImageRecordId());
    }

    public function galleryImages()
    {
        return $this->hasManyThrough(File::class, PhilanthropistFile::class, 'philanthropist_id', 'id', 'id', 'file_id')
            ->where('files.file_tag_id', '=', FileTag::galleryImageRecordId());
    }

    public function articleFiles()
    {
        return $this->hasManyThrough(File::class, PhilanthropistFile::class, 'philanthropist_id', 'id', 'id', 'file_id')
            ->where('files.file_tag_id', '=', FileTag::articleFileRecordId());
    }

    public function isFavoriteForUser()
    {
        return $this->hasOne(Favorite::class, 'philanthropist_id', 'id')
            ->where('user_id', '=', Auth::id());
    }

    public function getBirthDeathStr()
    {
        $birthDate = custom_date_format(isset($this->year_of_birth) ? $this->year_of_birth : null, isset($this->month_of_birth) ? $this->month_of_birth : null, isset($this->date_of_birth) ? $this->date_of_birth : null);
        $deathDate = custom_date_format(isset($this->year_of_death) ? $this->year_of_death : null, isset($this->month_of_death) ? $this->month_of_death : null, isset($this->date_of_death) ? $this->date_of_death : null);
        return '(' . ( strlen($birthDate) > 0 ? $birthDate : 'Unknown') . ' - ' .  (strlen($deathDate) > 0 ? $deathDate : 'Unknown') . ')';
    }

    public function getDateOfBirthFormatted()
    {
        return custom_date_format(isset($this->year_of_birth) ? $this->year_of_birth : null, isset($this->month_of_birth) ? $this->month_of_birth : null, isset($this->date_of_birth) ? $this->date_of_birth : null);
    }

    public function getDateOfDeathFormatted()
    {
        return custom_date_format(isset($this->year_of_death) ? $this->year_of_death : null, isset($this->month_of_death) ? $this->month_of_death : null, isset($this->date_of_death) ? $this->date_of_death : null);
    }

//    public function business(): HasOne
//    {
//        return $this->hasOne(Business::class);
//    }

}
