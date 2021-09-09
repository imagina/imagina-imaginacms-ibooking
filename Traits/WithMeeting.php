<?php

namespace Modules\Ibooking\Traits;



trait WithMeeting
{


	/**
   	* Boot trait method
   	*/
	public static function bootWithMeeting()
	{
	    //Listen event after create model
	    static::created(function ($model) {

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
	   	
	   
	    // Data Metting
		$dataToCreate['meetingAttr'] = [
			'title' => 'Reunion con Usuario - '.$model->reservation->customer->email,
			'startTime' => $model->start_date,
			'email' => $model->reservation->customer->email
		];

		// Entity
		$dataToCreate['entityAttr'] =[
			'id' => $model->id,
			'type' => get_class($model),  
		];

		//dd($dataToCreate);

		// Create meeting with Provider
		$meeting = app('Modules\Imeeting\Services\MeetingService')->create($dataToCreate);

		if(isset($meeting['errors']))
		    throw new \Exception($meeting['errors'], 500);

	}

    


}