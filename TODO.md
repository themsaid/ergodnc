# TODO

[x] Switch to using Sanctum guard by default
[x] Use the new [assertNotSoftDeleted](https://github.com/laravel/framework/pull/38886) method
[x] Use the new LazilyRefreshDatabase testing trait on the base test class


## List Reservations Endpoint

[x] Must be authenticated & email verified
[x] Token (if exists) must allow `reservations.show`
[x] Can only list their own reservations or reservations on their offices
[x] Allow filtering by office_id only for authenticated host
[x] Allow filtering by user_id only for authenticated user
[x] Allow filtering by date range
[x] Allow filtering by status
[x] Paginate

## Make Reservations Endpoint

[] Must be authenticated & email verified
[] Token (if exists) must allow `reservations.make`
[] Cannot make reservations on their own property
[] Validate no other reservation conflicts with the same time
[] Use locks to make the process atomic
[] Email user & host when a reservation is made
[] Email user & host on reservation start day
[] Generate WIFI password for new reservations (store encrypted)

## Cancel Reservation Endpoint

[] Must be authenticated & email verified
[] Token (if exists) must allow `reservations.cancel`
[] Can only cancel their own reservation
[] Can only cancel an active reservation that has a start_date in the future

## Housekeeping

[] Convert filtering reservations by date to Eloquent Scopes
[] Filter offices by tag
[] API should return the full URI of the image so that the consumer

