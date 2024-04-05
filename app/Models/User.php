<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_key',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_key', 'key');
    }

    public function favoritePhilanthropists()
    {
        return $this->hasMany(Favorite::class);
    }

    public function hasRole($role)
    {
        if (!$this->role()) {
            return false;
        }

        if ($this->role()->first()->key == $role) {
            return true;
        }
        return false;
    }

    public function isRoleEmpty()
    {
        return is_null($this->role()->first());
    }


    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function maskedEmail()
    {
        $prefix = Str::before($this->email, '@');
        $replacement = $prefix;
        if (strlen($prefix) > 1) {
            $replacement = substr($prefix,0,2);
            $replacement =  $replacement .str_repeat('*', strlen($prefix) - 2);
        }

        $domain = Str::after($this->email, '@');
        $replacementEmail = $domain;
        if (strlen($domain) > 1) {
            $replacementEmail = substr($domain,0,2);
            $replacementEmail =  $replacementEmail .str_repeat('*', strlen($domain) - 2);
        }
        $masked_email = $replacement . '@' . $replacementEmail;

        return $masked_email;
    }
}
