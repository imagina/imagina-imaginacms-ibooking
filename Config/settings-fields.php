<?php

return [

  'reservationWithPayment' => [
    'value' =>  "0",
    'name' => 'ibooking::reservationWithPayment',
    'type' => 'checkbox',
    'props' => [
      'label' => 'ibooking::common.settings.reservationWithPayment',
      'trueValue' => "1",
      'falseValue' => "0",
    ]
  ],
  'createExternalMeeting' => [
    'value' =>  "0",
    'name' => 'ibooking::createExternalMeeting',
    'type' => 'checkbox',
    'props' => [
      'label' => 'ibooking::common.settings.createExternalMeeting',
      'trueValue' => "1",
      'falseValue' => "0",
    ]
  ],

  'usersToNotify' => [
    'name' => 'ibooking::usersToNotify',
    'value' => [],
    'type' => 'select',
    'columns' => 'col-12 col-md-6',
    'loadOptions' => [
      'apiRoute' => 'apiRoutes.quser.users',
      'select' => ['label' => 'email', 'id' => 'id'],
    ],
    'props' => [
      'label' => 'ibooking::common.settings.usersToNotify',
      'multiple' => true,
      'clearable' => true,
    ],
  ],

  'formEmails' => [
    'name' => 'ibooking::formEmails',
    'value' => [],
    'type' => 'select',
    'columns' => 'col-12 col-md-6',
    'props' => [
      'useInput' => true,
      'useChips' => true,
      'multiple' => true,
      'hint' => 'ibooking::common.settingHints.emails',
      'hideDropdownIcon' => true,
      'newValueMode' => 'add-unique',
      'label' => 'ibooking::common.settings.emails'
    ],
  ],
  
  
  
  
];
