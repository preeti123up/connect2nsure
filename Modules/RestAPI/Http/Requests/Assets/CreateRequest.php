<?php

namespace Modules\RestAPI\Http\Requests\Assets;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Modules\RestAPI\Http\Requests\BaseRequest;
use Froiden\RestAPI\ApiResponse;

class CreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sn' => 'required|string',
            'category_id' => 'required|int',
            'model' => 'required|string',
            'brand' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
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
            && ($user->hasRole('admin') || $user->cans('add_assets'));
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $firstError = reset($errors); // Get the first error message
        throw new HttpResponseException(
            ApiResponse::make($firstError[0], 200)
        );
    }
}
