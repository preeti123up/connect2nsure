<?php

namespace Modules\RestAPI\Http\Requests\Auth;

use Modules\RestAPI\Http\Requests\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
       public function rules()
{
       return [
            'token' => 'required|exists:password_resets,token',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];
}

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
