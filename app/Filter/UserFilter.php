<?php


namespace App\Filter;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends QueryFilter
{
    public function name($value){
        if($value !== null){
            $this->builder->orWhere('name', 'like', "%$value%");
        }
    }

    public function email($value){
        $this->builder->orWhereIn('email', $value);
    }

    public function verified($value){
        if($value === 'on'){
            $this->builder->orWhereNull('email_verified_at');
        }
    }

    public function country($value){
        $this->builder->orWhereIn('country_id', $value);
    }

    public function continent($value){
        $this->builder->orWhereHas('country', function (Builder $query) use ($value) {
            $query->whereHas('continent', function (Builder $query) use ($value) {
                $query->WhereIn('continent', $value);
            });
        });
    }
}
