<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $timestamps = false;

    public function country()
    {
        return $this->hasOne('App\Models\Country');
    }
}
