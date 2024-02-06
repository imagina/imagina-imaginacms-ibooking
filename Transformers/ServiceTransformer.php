<?php

namespace Modules\Ibooking\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;

use Modules\Iforms\Transformers\FormTransformer;
use Modules\Ibooking\Entities\Service;

class ServiceTransformer extends CrudResource
{

	public function modelAttributes($request)
	{
    
    $form = $this->forms->first();

		return [
      "form" => $form ?? '',
      "formId" => $form->id ?? ''
		];

	}
    
}
