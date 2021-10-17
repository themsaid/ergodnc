<?php

namespace App\Http\Pipelines\Filtration;

use Closure;
use App\Http\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class FilterByVisitorId implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder->when(request('visitor_id'), 
            fn (Builder $query) => 
                $query->whereRelation('reservations', 'user_id', request('visitor_id'))
            );
    }
}
