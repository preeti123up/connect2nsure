<?php

namespace Modules\RestAPI\Http\Requests\ContractType;

use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{
    public function authorize()
    {
        $user = api_user();

        return in_array('contracts', $user->modules) && ($user->hasRole('admin') || $user->cans('view_contract'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
