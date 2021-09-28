# TODO

[] Switch to using Sanctum guard by default


[x] Delete all images when deleting an office
[x] Use the default disk to store public images so it's easier to switch to different drivers in production
[x] Use keyed implicit binding in the office image routes so laravel scopes to the office that the image belongs to [Tweet](https://twitter.com/themsaid/status/1441323002222637062)

## List Reservations Endpoint

[x] Must be authenticated & email verified
[x] Token (if exists) must allow `reservations.show`
[] Can only list their own reservations or reservations on their offices
[] Allow filtering by office_id only for authenticated host
[] Allow filtering by user_id only for authenticated user
[] Allow filtering by date range
[] Allow filtering by status
[] Paginate

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

## Handle Billing with Cashier
