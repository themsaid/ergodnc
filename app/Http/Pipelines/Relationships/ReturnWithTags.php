<?php

namespace App\Http\Pipelines\Relationships;

use App\Http\Pipelines\Pipe;
use Closure;

class ReturnWithTags implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);
        return $builder->with('tags');
    }
}
