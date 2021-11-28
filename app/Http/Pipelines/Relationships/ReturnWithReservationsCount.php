<?php

namespace App\Http\Pipelines\Relationships;

use Closure;
use App\Http\Pipelines\Pipe;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;

class ReturnWithReservationsCount implements Pipe
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);
        return $builder->withCount([
            'reservations' => 
                fn(Builder $query) => 
                    $query->whereStatus(Reservation::STATUS_ACTIVE)
        ]);
    }
}
