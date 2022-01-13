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
  * Format to hour
  * used = date(format, strtotime($item->end_date))
  */
  'hourFormat' => 'd-m-Y H:i:s',

];
