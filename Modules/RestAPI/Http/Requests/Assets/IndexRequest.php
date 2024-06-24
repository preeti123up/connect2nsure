<?php

namespace Modules\RestAPI\Http\Requests\Assets;

use Modules\RestAPI\Http\Requests\BaseRequest;
class IndexRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = api_user();
        return in_array('assets', $user->modules)
            && ($user->hasRole('admin') || $user->cans('view_assets'));
    }
}
