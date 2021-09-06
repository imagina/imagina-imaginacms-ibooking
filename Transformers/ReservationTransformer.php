<?php

namespace Modules\Ibooking\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;

use Modules\Imeeting\Traits\MeetingableTransformer;

class ReservationTransformer extends CrudResource
{

	use MeetingableTransformer;

	public function modelAttributes($request)
	{

		$data = [];

		if(setting('ibooking::createExternalMeeting'))
			$data = $this->getDataMeetings();

		return $data;

	}
    
}
