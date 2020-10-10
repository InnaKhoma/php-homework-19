<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\Label')->withTimestamps();
    }

    public function links()
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }
}
