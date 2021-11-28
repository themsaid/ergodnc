<?php

namespace App\Http\Pipelines\Filtration;

use Closure;
use App\Models\Office;
use App\Http\Pipelines\Pipe;

class OfficeAvailable implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        return $builder
            ->when(
                request('user_id') && auth()->user() && request('user_id') == auth()->id(),
                fn ($builder) => $builder,
                fn ($builder) => $builder->where('approval_status', Office::APPROVAL_APPROVED)->where('hidden', false)
            );
    }
}
