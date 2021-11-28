<?php

namespace App\Http\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Pipe
{
    public function handle($request, Closure $next);
}
