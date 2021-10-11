# TODO

## Make Reservations Endpoint

[x] Read request input from the validator output
[x] You cannot make a reservation on a pending or a hidden office
[] Test you can make a reservation starting next day but cannot make one on same day
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

