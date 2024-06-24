<?php

namespace Modules\RestAPI\Http\Requests\Leave;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Modules\RestAPI\Http\Requests\BaseRequest;
use Froiden\RestAPI\ApiResponse;


class CreateRequest extends BaseRequest
{
    public function authorize()
    {
        $user = api_user();

        return in_array('leaves', $user->modules)
            && ($user->hasRole('admin') || $user->hasRole('employee') || $user->cans('add_leave'));
    }

    public function rules()
    {
        return [
            'leave_type_id' => 'required',
            'duration' => 'required',
            'reason' => 'required',
            'status' => 'required',
            'multiStartDate' => 'required',
            'multiEndDate' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $firstError = reset($errors); // Get the first error message
        throw new HttpResponseException(
            ApiResponse::make($firstError[0], [], 200)
        );
    }
}
