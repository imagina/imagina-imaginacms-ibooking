<?php

return [
  'name' => 'Ibooking',

  //Media Fillables
  'mediaFillable' => [
    'category' => [
      'mainimage' => 'single'
    ],
    'service' => [
      'mainimage' => 'single'
    ],
    'resource' => [
      'mainimage' => 'single'
    ],
  ],

  /*
  * Format to hour - strtotime method
  * Used: Email
  */
  'hourFormat' => 'd-m-Y H:i A',

  /*
  *
  * Config to Activities in Igamification Module
  */
  'activities' => [
      [
        'system_name' => 'availability-organize',
        'title' => 'ibooking::activities.availability-organize.title',
        'status' => 1,
        'url' => 'ipanel/#/booking/resource/user/'
      ]
  ]

];
