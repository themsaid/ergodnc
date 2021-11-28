<?php

namespace App\Http\Pipelines\Filtration;

use Closure;
use App\Http\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class FilterByHostId implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder->when(request('host_id'),
            fn(Builder $query) =>
                $query->whereUserId(request('host_id')) 
            );
    }
}
