<?php

namespace Modules\Ibooking\Http\Controllers\Api;

use Carbon\Carbon as Time;
use Illuminate\Http\Request;
//Model
use Modules\Core\Icrud\Controllers\BaseCrudController;
use Modules\Ibooking\Entities\Resource;
use Modules\Ibooking\Entities\Status;
use Modules\Ibooking\Repositories\ResourceRepository;

class AvailabilityApiController extends BaseCrudController
{
    public $model;

    public $modelRepository;

    public function __construct(Resource $model, ResourceRepository $modelRepository)
    {
        $this->model = $model;
        $this->modelRepository = $modelRepository;
    }

    /**
     * @param serviceId (Required)
     * @param resourceId (Optional)
     * @return response (Array)
     */
    public function availability(Request $request)
    {
        // Get Params
        $params = $this->getParamsRequest($request)->filter;

        //Init repositories
        $serviceRepository = app('Modules\Ibooking\Repositories\ServiceRepository');
        $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');

        $paramsService = [
          'filter' => ['id' => $params->serviceId]
        ];

        // Get Schedule and WorkTimes to this Service ID
        $services = $serviceRepository->getItemsBy(json_decode(json_encode($paramsService)));

        $response = [];

        $paramsResource = [
          'includes' => 'schedule.workTimes'
        ];
        // Exist Resource ID
        if (isset($params->resourceId)) {
            $paramsResource['filter']['id'] = $params->resourceId;
        } else {
            $paramsResource['filter']['serviceId'] = $services->pluck('id')->toArray();
        }

        $resources = $this->modelRepository->getItemsBy(json_decode(json_encode($paramsResource)));

        $filterDate = isset($params->date) ? $params->date : date('Y-m-d');

        $paramsReservation = [
          'filter' => [
            'status' => [
              'where' => 'notIn',
              'value' => [Status::CANCELED] //Ignore only canceled
            ]
          ]
        ];

        $shiftTimes = $services->pluck('shift_time')->toArray();
        $totalShiftTime = array_sum($shiftTimes);

        // To Each Resource
        foreach ($resources as $resource) {
            $paramsReservation['filter']['resourceId'] = $resource->id;
            // Get Reservation Items from Resource
            $reservations = $reservationRepository->getItemsBy(json_decode(json_encode($paramsReservation)));

            // Get busy shifts
            $busyShifts = [];
            foreach ($reservations as $reservation) {
                // Add format to shifts
                array_push($busyShifts, [
                    'startTime' => Time::parse($reservation->start_date)->toTimeString(),
                    'endTime' => Time::parse($reservation->end_date)->toTimeString(),
                    'calendarDate' => Time::parse($reservation->start_date)->toDateString(),
                ]);
            }

            //Obtiene shifts por resources
            $shifts = $resource->schedule->getShifts([
                'shiftTime' => $totalShiftTime ?? 30,
                'dateRange' => isset($params->date) ? (array)$params->date : [],
                'timeRange' => isset($params->time) && ! is_null($params->time) ? $params->time : [],
                'busyShifts' => $busyShifts,
            ]);

            //Add Resource Data to the Shift
            foreach ($shifts as $shift) {
                array_push($response, array_merge($shift, ['resource' => $resource]));
            }
        }

        // Collect Response
        $response = collect($response)->filter(function ($item) {
          return !$item['isBusy'];
        })->values()->sortBy([['calendarDate', 'asc'], ['dayId', 'asc'], ['startTime', 'asc']]);

        return $response;
    }
}
