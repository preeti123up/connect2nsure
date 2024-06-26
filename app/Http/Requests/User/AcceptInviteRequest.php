<?php

namespace App\Http\Requests\User;

use App\Models\UserInvitation;
use Illuminate\Foundation\Http\FormRequest;

class AcceptInviteRequest extends FormRequest
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
        \Illuminate\Support\Facades\Validator::extend('check_superadmin', function ($attribute, $value, $parameters, $validator) {
            return !\App\Models\User::withoutGlobalScopes([\App\Scopes\ActiveScope::class, \App\Scopes\CompanyScope::class])
                ->where('email', $value)
                ->where('is_superadmin', 1)
                ->exists();
        });

        $invite = UserInvitation::where('invitation_code', request()->invite)
            ->where('status', 'active')
            ->first();

        $rules = [
            'name' => 'required',
            'password' => 'required|min:8'
        ];

        if (request()->has('email_address')) {
            $rules['email_address'] = 'required';
        }

        $global = global_setting();

        if ($global && $global->sign_up_terms == 'yes') {
            $rules['terms_and_conditions'] = 'required';
        }

        $rules['email'] = 'required|email:rfc|check_superadmin|unique:users,email,null,id,company_id,' . $invite->company->id;

        return $rules;
    }

    public function messages()
    {
        return [
            'email.check_superadmin' => __('superadmin.emailAlreadyExist'),
        ];
    }

}
