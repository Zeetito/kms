<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class AcademicYearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // $builder->where('academic_year_id', auth()->user()->user_semester()->academic_year_id);
        $builder->where('academic_year_id', User::find(2)->user_semester()->academic_year_id);
    }
}
