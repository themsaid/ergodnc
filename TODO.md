# TODO

[x] Identify who an admin is by adding an `is_admin` attribute to the users table.
[x] Show hidden and unapproved offices when filtering by `user_id` and the auth user matches the user so hosts can see all their listings

## Office Photos

[] Attaching photos to an office
[] Allow choosing a photo to become the featured photo
[] Deleting a photo — Must have at least one photo if it's approved.

## List Reservations Endpoint

[] Must be authenticated & email verified
[] Token (if exists) must allow `reservations.show`
[] Can only list their own reservations or reservations on their offices
[] Allow filtering by office_id
[] Allow filtering by user_id
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
