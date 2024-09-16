<?php

namespace Modules\Ibooking\Http\Controllers\Api;

use Carbon\Carbon as Time;
use Illuminate\Http\Request;
//Model
use Modules\Core\Icrud\Controllers\BaseCrudController;
use Modules\Ibooking\Entities\Reservation;
use Modules\Ibooking\Entities\ReservationItem;
use Modules\Ibooking\Entities\Resource;
use Modules\Ibooking\Entities\Service;
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

        // Get Schedule and WorkTimes to this Service ID
        $service = Service::find($params->serviceId);
        $response = [];

        // Exist Resource ID
        if (isset($params->resourceId)) {
            $resources = Resource::with('schedule.workTimes')->where('id', $params->resourceId)->get();
        } else {
            $resources = Resource::with('schedule.workTimes')->whereHas('services', function ($q) use ($service) {
                $q->where('ibooking__service_resource.service_id', $service->id);
            })->get();
        }

        $filterDate = isset($params->date) ? $params->date : date('Y-m-d');

        $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');
        $paramsReservation = [
          'filter' => [
            'resourceId' => 0,
            'status' => [
              'where' => 'notIn',
              'value' => [2,4]
            ]
          ]
        ];

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
                'shiftTime' => $service->shift_time ?? 30,
                'dateRange' => isset($params->date) ? [$params->date] : [],
                'timeRange' => isset($params->time) && ! is_null($params->time) ? $params->time : [],
                'busyShifts' => $busyShifts,
            ]);

            //Add Resource Data to the Shift
            foreach ($shifts as $shift) {
                array_push($response, array_merge($shift, ['resource' => $resource]));
            }
        }

        // Collect Response
        $response = collect($response)->sortBy([['calendarDate', 'asc'], ['dayId', 'asc'], ['startTime', 'asc']]);

        return $response;
    }
}
