<?php

namespace App\Http\Pipelines\Orders;

use Closure;
use App\Http\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class OrderByDistance implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);
        return $builder
            ->when(
                request('lat') && request('lng'),
                fn (Builder $builder) => $builder->nearestTo(request('lat'), request('lng')),
                fn (Builder $builder) => $builder->orderBy('id', 'ASC')
            );
    }
}
