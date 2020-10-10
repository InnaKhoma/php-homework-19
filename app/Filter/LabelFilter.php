<?php


namespace App\Filter;

use Illuminate\Database\Eloquent\Builder;

class LabelFilter extends QueryFilter
{
    public function email($value){
        $this->builder->orWhereIn('user_id', $value);
    }

    public function project($value){
        $this->builder->orWhereHas('projects', function (Builder $query) use ($value) {
            $query->WhereIn('projects.id', $value);
        });
    }
}
