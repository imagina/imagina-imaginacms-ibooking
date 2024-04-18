<?php

namespace Modules\Ibooking\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;
use Modules\Ibooking\Entities\Service;
use Modules\Iforms\Transformers\FormTransformer;

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
