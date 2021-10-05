# TODO

[] Convert filtering reservations by date to Eloquent Scopes
[] Include reservations that started before range and ended after range while filtering

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

[] Filter offices by tag
[] API should return the full URI of the image so that the consumer can load it easily

