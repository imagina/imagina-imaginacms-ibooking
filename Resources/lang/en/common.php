<?php

return [

    'table' => [
        'price' => 'Price',
        'start date' => 'Start date',
        'end date' => 'End date',
        'status' => 'Status',
    ],

    'settings' => [
        'createExternalMeeting' => 'Create external meeting',
        'reservationWithPayment' => 'Create Reservation with payment (Checkout Process)',
        'usersToNotify' => 'Users to Notify',
        'emails' => 'Webmaster Email',
        'reservationStatusDefault' => 'Reservation Status by Default',
        'waitingTimeToCancelReservation' => 'WaitingTime (in Minutes) to cancel reservation',
        'allowPublicReservation' => 'Allow public reservations',
        'autoUpdateReservationDates' => 'Update reservation dates with status changes',
    ],

    'settingHints' => [
        'emails' => 'Type the email and press enter key',
    ],

    'meeting' => [
        'title' => 'Meeting with User - ',
    ],
    'noAllowPublicReservations' => 'You could login to do a booking',

    'helpText' => [
      'autoUpdateReservationDates' => 'Enable this option to update the start date when the status changes to "In Progress" and the end date when it changes to "Completed" in reservations.'
    ]
];
