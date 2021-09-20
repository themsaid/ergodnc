<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfficeResource;
use App\Models\Office;
use App\Models\Reservation;
use App\Models\Validators\OfficeValidator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    public function index(): JsonResource
    {
        $offices = Office::query()
            ->where('approval_status', Office::APPROVAL_APPROVED)
            ->where('hidden', false)
            ->when(request('user_id'), fn($builder) => $builder->whereUserId(request('user_id')))
            ->when(request('visitor_id'),
                fn($builder) => $builder->whereRelation('reservations', 'user_id', '=', request('visitor_id'))
            )
            ->when(
                request('lat') && request('lng'),
                fn($builder) => $builder->nearestTo(request('lat'), request('lng')),
                fn($builder) => $builder->orderBy('id', 'ASC')
            )
            ->with(['images', 'tags', 'user'])
            ->withCount(['reservations' => fn($builder) => $builder->whereStatus(Reservation::STATUS_ACTIVE)])
            ->paginate(20);

        return OfficeResource::collection(
            $offices
        );
    }

    public function show(Office $office): JsonResource
    {
        $office->loadCount(['reservations' => fn($builder) => $builder->where('status', Reservation::STATUS_ACTIVE)])
            ->load(['images', 'tags', 'user']);

        return OfficeResource::make($office);
    }

    public function create(): JsonResource
    {
        abort_unless(auth()->user()->tokenCan('office.create'),
            Response::HTTP_FORBIDDEN
        );

        $attributes = (new OfficeValidator())->validate(
            $office = new Office(),
            request()->all()
        );

        $attributes['approval_status'] = Office::APPROVAL_PENDING;
        $attributes['user_id'] = auth()->id();

        $office = DB::transaction(function () use ($office, $attributes) {
            $office->fill(
                Arr::except($attributes, ['tags'])
            )->save();

            if (isset($attributes['tags'])) {
                $office->tags()->attach($attributes['tags']);
            }

            return $office;
        });

        return OfficeResource::make(
            $office->load(['images', 'tags', 'user'])
        );
    }

    public function update(Office $office): JsonResource
    {
        abort_unless(auth()->user()->tokenCan('office.update'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('update', $office);

        $attributes = (new OfficeValidator())->validate($office, request()->all());

        DB::transaction(function () use ($office, $attributes) {
            $office->update(
                Arr::except($attributes, ['tags'])
            );

            if (isset($attributes['tags'])) {
                $office->tags()->sync($attributes['tags']);
            }
        });

        return OfficeResource::make(
            $office->load(['images', 'tags', 'user'])
        );
    }
}
