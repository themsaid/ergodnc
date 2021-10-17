<?php

namespace App\Http\Pipelines\Filtration;

use Closure;
use App\Http\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class FilterByUserId implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder->when(request('user_id'), fn($builder) => $builder->whereUserId(request('user_id')));
    }
}
