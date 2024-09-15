<?php

namespace Modules\Ibooking\Entities;

use Modules\Core\Icrud\Entities\CrudStaticModel;

class Status extends CrudStaticModel
{
  const PENDING = 0;
  const APPROVED = 1;
  const CANCELED = 2;
  const INPROGRESS = 3;
  const COMPLETED = 4;

  public function __construct()
  {
    $this->records = [
      self::PENDING => [
        'title' => trans('ibooking::reservations.status.pending'),
        'color' => ''
      ],
      self::APPROVED => [
        'title' => trans('ibooking::reservations.status.approved'),
        'color' => ''
      ],
      self::CANCELED => [
        'title' => trans('ibooking::reservations.status.canceled'),
        'color' => ''
      ],
      self::INPROGRESS => [
        'title' => trans('ibooking::reservations.status.inProgress'),
        'color' => ''
      ],
      self::COMPLETED => [
        'title' => trans('ibooking::reservations.status.completed'),
        'color' => ''
      ],
    ];
  }

  public function convertToSettings()
  {
    $statuses = $this->records;
    $statusSetting = [];
    foreach ($statuses as $key => $status) {
      array_push($statusSetting, [
        'label' => $status['title'],
        'value' => $key
      ]);
    }
    //\Log::info("StatusSetting: ".json_encode($statusSetting));
    return $statusSetting;
  }

  public function get($statusId)
  {
    if (isset($this->records[$statusId])) {
      return $this->records[$statusId]['title'];
    }

    return $this->records[self::PENDING]['title'];
  }
}
