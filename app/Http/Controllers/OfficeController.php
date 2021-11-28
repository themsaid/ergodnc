<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Reservation;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OfficeResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Validators\OfficeValidator;
use App\Notifications\OfficePendingApproval;
use Illuminate\Support\Facades\Notification;
use App\Http\Pipelines\Orders\OrderByDistance;
use Illuminate\Validation\ValidationException;
use App\Http\Pipelines\Filtration\FilterByTags;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Pipelines\Filtration\FilterByUserId;
use App\Http\Pipelines\Filtration\OfficeAvailable;
use App\Http\Pipelines\Filtration\FilterByVisitorId;
use App\Http\Pipelines\Relationships\ReturnWithTags;
use App\Http\Pipelines\Relationships\ReturnWithUser;
use App\Http\Pipelines\Relationships\ReturnWithImages;
use App\Http\Pipelines\Relationships\ReturnWithReservationsCount;

class OfficeController extends Controller
{
    public function index(): JsonResource
    {
        $offices = app(Pipeline::class)
                ->send(Office::query())
                ->through([
                    OfficeAvailable::class,
                    FilterByUserId::class,
                    FilterByVisitorId::class,
                    OrderByDistance::class,
                    FilterByTags::class,
                    ReturnWithImages::class,
                    ReturnWithTags::class,
                    ReturnWithUser::class,
                    ReturnWithReservationsCount::class,
                ])
                ->thenReturn()
                ->paginate(request('per_page', 20));

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

        Notification::send(User::where('is_admin', true)->get(), new OfficePendingApproval($office));

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

        $office->fill(Arr::except($attributes, ['tags']));

        if ($requiresReview = $office->isDirty(['lat', 'lng', 'price_per_day'])) {
            $office->fill(['approval_status' => Office::APPROVAL_PENDING]);
        }

        DB::transaction(function () use ($office, $attributes) {
            $office->save();

            if (isset($attributes['tags'])) {
                $office->tags()->sync($attributes['tags']);
            }
        });

        if ($requiresReview) {
            Notification::send(User::where('is_admin', true)->get(), new OfficePendingApproval($office));
        }

        return OfficeResource::make(
            $office->load(['images', 'tags', 'user'])
        );
    }

    public function delete(Office $office)
    {
        abort_unless(auth()->user()->tokenCan('office.delete'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('delete', $office);

        throw_if(
            $office->reservations()->where('status', Reservation::STATUS_ACTIVE)->exists(),
            ValidationException::withMessages(['office' => 'Cannot delete this office!'])
        );

        $office->images()->each(function ($image) {
            Storage::delete($image->path);

            $image->delete();
        });

        $office->delete();
    }
}
