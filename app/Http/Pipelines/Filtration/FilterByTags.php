<?php

namespace App\Http\Pipelines\Filtration;

use Closure;
use App\Http\Pipelines\Pipe;

class FilterByTags implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder
            ->when(request('tags'),
                fn($builder) => $builder->whereHas(
                    'tags',
                    fn ($builder) => $builder->whereIn('id', request('tags')),
                    '=',
                    count(request('tags'))
                )
            );
    }
}
