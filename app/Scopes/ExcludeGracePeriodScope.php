<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExcludeGracePeriodScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Use dynamic admin prefix from global settings
        $adminPrefix = trim(setting('site_admin_prefix', 'global'), '/');

        // Apply only if this is an admin route (e.g. admin/user/*)
        if (request()->is($adminPrefix . '/*')) {
            $builder->where('in_grace_period', false);
        }

        // Optionally still apply in console (e.g. scheduled jobs)
        if (app()->runningInConsole()) {
            $builder->where('in_grace_period', false);
        }
    }
}

