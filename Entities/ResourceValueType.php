<?php

namespace Modules\Ibooking\Entities;

use Modules\Core\Icrud\Entities\CrudStaticModel;

class ResourceValueType extends CrudStaticModel
{
  const PERCENTAGE = 1;
  const PRICE = 2;

  public function __construct()
  {
    $this->records = [
      self::PERCENTAGE => [
        'id' => self::PERCENTAGE,
        'title' => trans('ibooking::common.percentage')
      ],
      self::PRICE => [
        'id' => self::PRICE,
        'title' => trans('ibooking::common.price')
      ]
    ];
  }
}
