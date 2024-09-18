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
        $records = [
            self::PENDING => [
                'id' => self::PENDING,
                'title' => trans('ibooking::reservations.status.pending'),
                'color' => '#f39c12', // Orange (indicating waiting or action needed)
                'icon' => 'fas fa-hourglass-half', // Hourglass indicating waiting
                'nextStatus' => [self::APPROVED, self::CANCELED]
            ],
            self::APPROVED => [
                'id' => self::APPROVED,
                'title' => trans('ibooking::reservations.status.approved'),
                'color' => '#28a745', // Green (indicating success or approval)
                'icon' => 'fas fa-check-circle', // Checkmark indicating approval
                'nextStatus' => [self::PENDING, self::CANCELED, self::INPROGRESS, self::COMPLETED]
            ],
            self::CANCELED => [
                'id' => self::CANCELED,
                'title' => trans('ibooking::reservations.status.canceled'),
                'color' => '#dc3545', // Red (indicating cancellation or error)
                'icon' => 'fas fa-times-circle', // Cross indicating cancellation
                'nextStatus' => [self::PENDING]
            ],
            self::INPROGRESS => [
                'id' => self::INPROGRESS,
                'title' => trans('ibooking::reservations.status.inProgress'),
                'color' => '#17a2b8', // Light blue (indicating ongoing process)
                'icon' => 'fas fa-spinner', // Spinner indicating in-progress
                'nextStatus' => [self::CANCELED, self::COMPLETED]
            ],
            self::COMPLETED => [
                'id' => self::COMPLETED,
                'title' => trans('ibooking::reservations.status.completed'),
                'color' => '#007bff', // Blue (indicating completion or success)
                'icon' => 'fas fa-check-square', // Check-square indicating completion
                'nextStatus' => [self::PENDING]
            ],
        ];
        // Replace nextStatus with full status records
        foreach ($records as $status => &$details) {
            $details['nextStatus'] = array_map(function($nextStatus) use ($records) {
                return $records[$nextStatus]; // Replace status with full record
            }, $details['nextStatus']);
        }
        //Set records
        $this->records = $records;
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
