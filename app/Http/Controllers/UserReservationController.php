<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Office;
use App\Models\Reservation;
use App\Rules\NotOwnOffice;
use App\Rules\NoReservationsForPeriod;
use App\Rules\ValidOffice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserReservationController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->tokenCan('reservations.show'),
            Response::HTTP_FORBIDDEN
        );

        validator(request()->all(), [
            'status' => [Rule::in([Reservation::STATUS_ACTIVE, Reservation::STATUS_CANCELLED])],
            'office_id' => ['integer'],
            'from_date' => ['date', 'required_with:to_date'],
            'to_date' => ['date', 'required_with:from_date', 'after:from_date'],
        ])->validate();

        $reservations = Reservation::query()
            ->where('user_id', auth()->id())
            ->when(request('office_id'),
                fn($query) => $query->where('office_id', request('office_id'))
            )->when(request('status'),
                fn($query) => $query->where('status', request('status'))
            )->when(request('from_date') && request('to_date'),
                fn($query) => $query->betweenDates(request('from_date'), request('to_date'))
            )
            ->with(['office.featuredImage'])
            ->paginate(20);

        return ReservationResource::collection(
            $reservations
        );
    }

    public function create()
    {
        abort_unless(auth()->user()->tokenCan('reservations.make'),
            Response::HTTP_FORBIDDEN
        );

        /** @var null|Office $office */
        $office = Office::query()->find(request('office_id'));

        validator(request()->all(), [
            'office_id' => [
                'required',
                'integer',
                new ValidOffice($office),
                new NotOwnOffice($office),
                new NoReservationsForPeriod($office)
            ],
            'start_date' => ['required', 'date:Y-m-d', 'after:today'],
            'end_date' => ['required', 'date:Y-m-d', 'after:start_date'],
        ])->validate();

        $reservation = Cache::lock('reservations_office_' . $office->id, 10)->block(3, function () use ($office) {
            $numberOfDays = Carbon::parse(request('end_date'))->endOfDay()->diffInDays(
                    Carbon::parse(request('start_date'))->startOfDay()
                ) + 1;

            $price = $numberOfDays * $office->price_per_day;

            if ($numberOfDays >= 28 && $office->monthly_discount) {
                $price = $price - ($price * $office->monthly_discount / 100);
            }

            return Reservation::create([
                'user_id' => auth()->id(),
                'office_id' => $office->id,
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                'status' => Reservation::STATUS_ACTIVE,
                'price' => $price,
            ]);
        });

        return ReservationResource::make(
            $reservation->load('office')
        );
    }
}
