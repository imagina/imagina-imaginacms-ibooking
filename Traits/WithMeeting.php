<?php

namespace Modules\Ibooking\Traits;

use Modules\Imeeting\Entities\Meeting;

trait WithMeeting
{


	/**
   	* Boot trait method
   	*/
	public static function bootWithMeeting()
	{
	    //Listen event after create model
	    static::created(function ($model) {

	    	\Log::info('Ibooking: Service With Meeting: '.$model->service->with_meeting);

	    	// Validate Service With Meeting
	     	if($model->service->with_meeting)
	      		$model->createMeeting($model);

	    });
	    
	}

	/**
   	* Create meeting to entity
   	*/
	public function createMeeting($model)
	{
	   	
	   	\Log::info('Ibooking: Trait WithMeeting - createMeeting');

	    // Data Metting
		$dataToCreate['meetingAttr'] = [
			'title' => 'Reunion con Usuario - '.$model->reservation->customer->email,
			'startTime' => $model->start_date,
			'email' => $model->resource->options->email //Host
		];

		// Entity
		$dataToCreate['entityAttr'] =[
			'id' => $model->id,
			'type' => get_class($model),  
		];

		//\Log::info('Ibooking: Trait WithMeeting - data: '.json_encode($dataToCreate));

		// Create meeting with Provider
		$meeting = app('Modules\Imeeting\Services\MeetingService')->create($dataToCreate);

		if(isset($meeting['errors'])){
		    throw new \Exception($meeting['errors'], 500);
		}else{

			$idsToNotify = [$model->reservation->customer->id,$model->resource->created_by];

			\Log::info('Ibooking: Trait WithMeeting - Pusher Notification IDS: '.json_encode($idsToNotify));

			//Send pusher notification
	        app('Modules\Notification\Services\Inotification')->to(['broadcast' => $idsToNotify])->push([
	            "title" => "Reunion Creada",
	            "message" => "Reunion Creada",
	            "link" => url(''),
	            "frontEvent" => [
	              "name" => "ibooking.new.reservation",
	            ],
	            "setting" => ["saveInDatabase" => 1]
	         ]);
          	

		}

	}

	/*
	* Entity Relation with Meetings
	*/
    public function meetings()
  	{
    	return $this->morphMany(Meeting::class, 'entity');
  	}

    


}