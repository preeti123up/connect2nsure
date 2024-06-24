<?php

namespace App\Http\Requests\SuperAdmin\EnquiryUs;

use GuzzleHttp\Client;
use App\Models\GlobalSetting;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EnquiryRequest extends FormRequest
{

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $global = GlobalSetting::first();
        $rules = [];

        if ($this->input('form_name') === 'contactUs_h') {
            $rules = [
                'name_h' => 'required|string|max:255',
                'company_name_h' => 'required|string|max:255',
                'mobile_h' => 'required|digits:10|regex:/^[6-9][0-9]*$/',
                'email_h' => 'required|regex:/^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b$/i',
            ];
        } else {
            $rules = [
                'name' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'mobile' => 'required|digits:10|regex:/^[6-9][0-9]*$/',
                'email' => 'required|unique:enquiries|regex:/^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b$/i',

            ];
        }

        return $rules;
    }
    public function messages()
    {
        $messages = [];

        if ($this->input('form_name') === 'contactUs_h') {
            $messages = [
                'name_h.required' => 'Your name is required.',
                'name_h.string' => 'Your name must be a string.',
                'name_h.max' => 'Your name may not be greater than 255 characters.',
                'company_name_h.required' => 'Company name is required.',
                'company_name_h.string' => 'Company name must be a string.',
                'company_name_h.max' => 'Company name may not be greater than 255 characters.',
                'mobile_h.required' => 'Mobile number is required.',
                'mobile_h.digits' => 'Mobile number must be 10 digits.',
                'mobile_h.regex' => 'Mobile Format is invalid.',
                'email_h.required' => 'Email is required.',
                'email_h.regex' => 'Email format is invalid',
            ];
        } 

        return $messages;
    }

}
