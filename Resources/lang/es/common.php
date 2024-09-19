<?php

return [

    'table' => [
        'price' => 'Precio',
        'start date' => 'Fecha de Inicio',
        'end date' => 'Fecha de Fin',
        'status' => 'Estado',
    ],

    'settings' => [
        'createExternalMeeting' => 'Crear External Meeting',
        'reservationWithPayment' => 'Crear Reservacion con pago (Proceso de Checkout)',
        'usersToNotify' => 'Usuarios para enviar notificaciones',
        'emails' => 'Emails para enviar notificaciones',
        'reservationStatusDefault' => 'Estado de la Reserva por defecto (Al Crearse)',
        'waitingTimeToCancelReservation' => 'Tiempo de espera (En Minutos) para cancelar una reservación',
        'allowPublicReservation' => 'Permitir reservas publicas',
        'autoUpdateReservationDates' => 'Actualizar fechas de reserva con cambios de estados',
    ],

    'settingHints' => [
        'emails' => 'Ingresa el correo y presiona enter',
    ],

    'meeting' => [
        'title' => 'Reunion con Usuario - ',
    ],
    'noAllowPublicReservations' => 'Debes de iniciar sesión para generar una reserva',

    'helpText' => [
       'autoUpdateReservationDates' => 'Activa esta opción para actualizar la fecha de inicio al cambiar el estado a "En progreso" y la fecha de finalización al cambiar el estado a "Completado" en las reservas.'
    ]
];
