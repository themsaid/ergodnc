<?php

namespace App\Http\Pipelines\Filtration;

use App\Http\Pipelines\Pipe;
use App\Models\Office;
use Closure;

class IsApproved implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder
            ->where('approval_status', Office::APPROVAL_APPROVED);
    }
}
