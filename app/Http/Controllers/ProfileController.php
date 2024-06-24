<?php

namespace App\Http\Controllers;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\User\UpdateProfile;
use App\Models\EmployeeDetails;
use App\Models\User;
use App\Scopes\ActiveScope;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends AccountBaseController
{

    public function update(UpdateProfile $request, $id)
    {
        $redirect = false;

        // For profile image to be uploaded locally
        $user = User::withoutGlobalScope(ActiveScope::class)->findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->salutation = $request->salutation;
        $user->gender = $request->gender;
        $user->country_id = $request->country_id;
        $user->country_phonecode = $request->country_phonecode;
        $user->mobile = $request->mobile;
        $user->email_notifications = $request->email_notifications;
        $user->locale = $request->locale;
        $user->rtl = $request->rtl;
        $user->google_calendar_status = $request->google_calendar_status;

        if ($request->image_delete == 'yes') {
            Files::deleteFile($user->image, 'avatar');
            $user->image = null;
        }

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image, 'avatar');
            $user->image = Files::uploadLocalOrS3($request->image, 'avatar', 300);
        }

        if ($request->has('telegram_user_id')) {
            $user->telegram_user_id = $request->telegram_user_id;
        }

        if ($user->isDirty('locale')) {
            $redirect = true;
        }


        // Update email in userauth also
        if ($user->isDirty('email')) {

            $userCount = User::withoutGlobalScopes([CompanyScope::class, ActiveScope::class])->where('user_auth_id', $user->user_auth_id)->count();


            if ($userCount > 1) {
                $userAuth = $user->userAuth->replicate();
                $userAuth->email = $request->email;
                $userAuth->save();

                $user->user_auth_id = $userAuth->id;
            }
            else {
                $user->userAuth->email = $request->email;
                $user->userAuth->save();
            }
        }

        $user->save();

        if (!is_null($request->password)) {
            $user->userAuth->update(['password' => Hash::make($request->password)]);
        }

        if ($user->clientDetails) {
            $fields = $request->only($user->clientDetails->getFillable());

            $user->clientDetails->fill($fields);
            $user->clientDetails->save();
        }

        // adding address to employee_details
        // WORKSUITESAAS move outside addEmployeeDetail for worksuitesaas
        if(!user()->is_superadmin) {
            $this->addEmployeeDetail($request, $user);
        }

        session()->forget('user');

        $this->logUserActivity($user->id, 'messages.updatedProfile');

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('profile-settings.index');

            // WORKSUITESAAS
            if(user()->is_superadmin) {
                $redirectUrl = route('superadmin.settings.super-admin-profile.index');
            }
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl, 'redirect' => $redirect]);
    }

    public function addEmployeeDetail($request, $user)
    {
        $employee = EmployeeDetails::where('user_id', $user->id)->first();

        if (empty($employee)) {
            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
        }

        $employee->date_of_birth = $request->date_of_birth ? Carbon::createFromFormat($this->company->date_format, $request->date_of_birth)->format('Y-m-d') : null;
        $employee->address = $request->address;
        $employee->slack_username = $request->slack_username;
        $employee->about_me = $request->about_me;

        if (in_array('employee', user_roles())) {
            $employee->marital_status = $request->marital_status;
            $employee->marriage_anniversary_date = $request->marriage_anniversary_date ? Carbon::createFromFormat($this->company->date_format, $request->marriage_anniversary_date)->format('Y-m-d') : null;
        }

        $employee->save();
    }

    public function darkTheme(Request $request)
    {
        $user = user();
        $user->dark_theme = $request->darkTheme;
        $user->save();
        session()->forget('user');
        return Reply::success(__('messages.updateSuccess'));
    }

    public function updateOneSignalId(Request $request)
    {
        $user = user();
        $user->onesignal_player_id = $request->userId;
        $user->save();
        session()->forget('user');
    }

}
