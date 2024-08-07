<?php

namespace Modules\Ibooking\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateResourceRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
          'assigned_to_id' => 'required',
        ];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
