<?php

namespace Modules\RestAPI\Http\Requests\Auth;

use Illuminate\Http\Request;
use Modules\RestAPI\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $requestData = $this->api_decrypt_data_auths($this->input('body'));
        Request::merge([
            'email' => $requestData['email'],
            'password' => $requestData['password'],
            'fcm_token' => $requestData['fcm_token'],
        ]);    
       $rules = [
            'password' => 'required',
        ];
        if (request()->has('branch_login')) {
            $rules['email'] = ''; 
        } else {
            $rules['email'] = 'required';
        }
        return $rules;
        
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

    /**
     * Decrypt the request data using the provided helper function.
     *
     * @param string $encryptedData
     * @return array
     */
    private function api_decrypt_data_auths($encryptedData)
    {
        // Use $this-> when calling the method within the class
        return api_decrypt_data_auth($encryptedData);
    }
    
}
