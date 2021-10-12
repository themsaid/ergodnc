# TODO

## Make Reservations Endpoint

[x] Read request input from the validator output
[x] You cannot make a reservation on a pending or a hidden office
[x] Test you can make a reservation starting next day but cannot make one on same day
[x] Email user & host when a reservation is made
[x] Email user & host on reservation start day
[x] Generate WIFI password for new reservations (store encrypted)

## Cancel Reservation Endpoint

[x] Must be authenticated & email verified
[x] Token (if exists) must allow `reservations.cancel`
[x] Can only cancel their own reservation
[x] Can only cancel an active reservation that has a start_date in the future

## Housekeeping

[x] Filter offices by tag
[x] API should return the full URI of the image so that the consumer can load it easily
[] Test SendDueReservationsNotifications command

