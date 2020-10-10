<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

    public function labels()
    {
        return $this->hasMany('App\Models\Label');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function links()
    {
        return $this->belongsToMany('App\Models\Project');
    }

    public function continent()
    {
        return $this->hasOneThrough('App\Models\Continent', 'App\Models\Country');
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }
}
