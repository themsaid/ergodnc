<?php

namespace App\Http\Pipelines\Filtration;

use Closure;
use App\Http\Pipelines\Pipe;

class IsNotHidden implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder->where('hidden', false);
    }
}
