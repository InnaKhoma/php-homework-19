<?php


namespace App\Filter;

use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends QueryFilter
{
    public function email($value){
        $this->builder->orWhereIn('user_id', $value);
    }

    public function label($value){
        $this->builder->orWhereHas('labels', function (Builder $query) use ($value) {
            $query->WhereIn('name', $value);
        });
    }

    public function continent($value){
        $this->builder->orWhereHas('user', function (Builder $query) use ($value) {
            $query->WhereHas('country', function (Builder $query) use ($value) {
                $query->WhereHas('continent', function (Builder $query) use ($value) {
                    $query->WhereIn('continent', $value);
                });
            });
        });
    }
}
