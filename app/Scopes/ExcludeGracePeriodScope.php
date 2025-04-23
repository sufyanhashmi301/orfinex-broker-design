<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExcludeGracePeriodScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('in_grace_period', false);
    }
}
