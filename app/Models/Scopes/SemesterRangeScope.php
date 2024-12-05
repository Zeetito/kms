<?php

namespace App\Models\Scopes;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class SemesterRangeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $current_semester_id = auth()->user()->semester_id;
        $current_semester = Semester::find($current_semester_id);
        $next_semester = Semester::where('id', '>', $current_semester_id)->first();

        if($next_semester){
            $builder->whereDate('created_at', '>=', $current_semester->start_date)->whereDate('created_at', '<=', $next_semester->start_date);
        }else{

            $builder->whereDate('created_at', '>=', $current_semester->start_date);
        }

    }
}
