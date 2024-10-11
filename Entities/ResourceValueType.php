<?php

namespace Modules\Ibooking\Entities;

use Modules\Core\Icrud\Entities\CrudStaticModel;

class ResourceValueType extends CrudStaticModel
{
  const PERCENTAGE = 'percentage';
  const PRICE = 'price';

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
