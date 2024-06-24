<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\User;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Modules\RestAPI\Entities\PersonalAccessToken;
use Carbon\Carbon;
use App\Models\UserAuth;
use App\Models\AttendanceSetting;
use App\Models\EmployeeShiftSchedule;
use App\Models\EmployeeDetails;
use Modules\RestAPI\Http\Requests\Auth\EmailVerifyRequest;
use Modules\RestAPI\Http\Requests\Auth\LoginRequest;
use Modules\RestAPI\Http\Requests\Auth\RefreshTokenRequest;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\PasswordReset;
use  Modules\RestAPI\Http\Requests\Auth\ResetPasswordRequest;
use Modules\RestAPI\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiBaseController
{

     public function login(LoginRequest $request)
    {

        // Modifications to this function may also require modifications to
      
        $email = $request['email'];
        $password = $request['password'];
        $days = 365;
        $minutes = 60 * 60 * $days;
        $claims = ['exp' => (int)now()->addYear()->getTimestamp(), 'remember' => 1, 'type' => 1];
        $check = false;

         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $check = auth()->attempt(['email' => $email, 'password' => $password]);
           
         } 
        elseif (preg_match('/^\d{10}$/', $email)) {
            $check = User::Where("mobile",$email)->first();
            if($check !== null){
                $check = auth()->attempt(['email' => $check->email, 'password' => $password]);
            }
           }
           if ($request->has('branch_login')) {
            $check = User::Where("branch_login",$request->branch_login)->first();
            if($check !== null){
                $check = auth()->attempt(['email' => $check->email, 'password' => $password]);
            }
           }

        if ($check !== null && $check) {
            $user = auth()->user()->user;
             $attendanceSettings = EmployeeShiftSchedule::where('user_id', $user->id)
        ->whereDate('date', Carbon::today())
        ->first(); 
                
        if ($attendanceSettings) {
            $attendanceSettings = $attendanceSettings->shift; 
        } else {
            $attendanceSettings = AttendanceSetting::first();
            if ($attendanceSettings) {
                $attendanceSettings= $attendanceSettings->shift;
            } else {
                $attendanceSettings = null;
            }
        }  

            if ($user && $user->status === 'deactive') {
                return response()->json(['body'=>api_encrypt_data_auth([
                    'status' => 'fail',
                    'status_code'=>401,
                    'message' =>'User account blocked'
                ])]);
            }
             PersonalAccessToken::where('name',$user->id)->delete();
            $expiry = now()->addYear();
            $tokenName = $user->id;
            
            $token = auth()->user()->createToken($tokenName, ['*'], $expiry, $claims)->plainTextToken;


            if (isWorksuiteSaas() && $user->is_superadmin) {
                return response()->json(['body'=>api_encrypt_data_auth([
                    'status' => 'fail',
                    'status_code'=>401,
                    'message' => 'User account blocked'
                ])]);
            }

            $user_data = User::find($user->id);
            
             if(in_array('admin', user_roles())){
                 $reportingCount =  EmployeeDetails::where('company_id',$user->company_id)->count();
             }else{
                 $reportingCount =  EmployeeDetails::where('reporting_to',$user->id)->count();
             }
             if($user_data->fcm_token == NULL){
                $change_password  = true;
            }else{
                $change_password  = false;
            }
            $user_data->fcm_token =$request->fcm_token;
            $user_data->save();
            $user = ApiResponse::make('Login Successfully',[
                'token' => $token,
                'user'=> $user,
                'isRepotingManager' => $reportingCount,
                'isAdmin' =>in_array('admin', user_roles())?True:False,
               'faceid_license' =>in_array('visual_attendance', api_user()->modules)?"Rj+aXmcgAwlgHJb1GVWsEPkaEQYLyt1nsy4ByZQ1Bf2RHNgWcfHmj5EYQy3F0elRFf5zH7MYo4TGzKW39Ym8Kc47Y8gBEd2qBGHCnv1fFU4VW1L4t0AJlaw24UEIlQb3wJr/C7/pj8pvVUz4P0Sn3riVTac4BYg2rJBR9hrt8mJt2KMTAEPn7bXob6HhCGTegrCqKM74NYW8XK0zAigAbCdQ4Vpcu+QH5O5YP3j+pnHfM/HLbiqZNEqwADlrD6O62ljrqGieCUb5J+tkewT6CsOKW6w+YbKQ1YCtMWeVfHuvHFvedlzaLthKvzyh7ckd3PWuzluNqZp338HJNLO6eg==":null,
                'expires' => $expiry->format('Y-m-d\TH:i:sP'),
                'expires_in' => $minutes,
                'change_password' => $change_password,
                 'office_start_time' => $attendanceSettings->office_start_time,
            'office_end_time' => $attendanceSettings->office_end_time,
            'outside_clcokin' => ($attendanceSettings->allow_outside_clockin)=="yes" ?true:false,
             'company_created_at' => api_user()->company->created_at,
            ]);
            return response()->json(['body'=>api_encrypt_data_auth($user->original)]);
        }

        return response()->json(['body'=>api_encrypt_data_auth([
            'status' => 'fail',
            'status_code'=>200,
            'message' => 'Wrong credentials provided'
        ])]);
    }

   public function logout(Request $request)
    {
        $user_data = User::where('id',api_user()->id)->first();
        $user_data->fcm_token = NULL;
        $user_data->save();
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return ApiResponse::make('Token invalidated successfully');
    }

    public function refresh(RefreshTokenRequest $request)
    {
        $user = auth()->user();

        if ($user->status === 'deactive') {
            $user->currentAccessToken()->delete();
            return [
                    'status' => 'fail',
                    'status_code'=>401,
                    'message' => 'User account blocked'
                ];
        }

        $expiry = now()->addHour();
        $claims = $user->currentAccessToken()->claims;

        $currentToken = $user->currentAccessToken()->id;
        $tokenName = Str::slug($user->name . ' ' . $user->id);

        $newToken = $user->createToken($tokenName, ['*'], now()->addHour(), $claims)->plainTextToken;

        // Revoke Old Token
        $user->tokens()->where('id', $currentToken)->delete();

        return ApiResponse::make('Token refreshed successfully', [
            'token' => $newToken,
            'expires' => $expiry->format('Y-m-d\TH:i:sP'),
            'expires_in' =>  60, // 60 minutes
        ]);

    }

     public function me()
    {
        
         $attendanceSettings = EmployeeShiftSchedule::where('user_id', api_user()->id)
        ->whereDate('date', Carbon::today())
        ->first(); 
                
        if ($attendanceSettings) {
            $attendanceSettings = $attendanceSettings->shift; 
        } else {
            $attendanceSettings = AttendanceSetting::first();
            if ($attendanceSettings) {
                $attendanceSettings= $attendanceSettings->shift;
            } else {
                $attendanceSettings = api_user()->company->attendanceSetting;
            }
        }  
         if(in_array('admin', user_roles())){
                $reportingCount =  EmployeeDetails::where('company_id',api_user()->company_id)->count();
            }else{
               $reportingCount =  EmployeeDetails::where('reporting_to',api_user()->id)->where('company_id',api_user()->company_id)->count();
            }
        return ApiResponse::make('Auth User', [
            'user' => api_user(),
            'fcm' => api_user()->fcm_token==NULL?true:false,
            'isRepotingManager' => $reportingCount,
            'faceid_license' =>in_array('visual_attendance', api_user()->modules)?"Rj+aXmcgAwlgHJb1GVWsEPkaEQYLyt1nsy4ByZQ1Bf2RHNgWcfHmj5EYQy3F0elRFf5zH7MYo4TGzKW39Ym8Kc47Y8gBEd2qBGHCnv1fFU4VW1L4t0AJlaw24UEIlQb3wJr/C7/pj8pvVUz4P0Sn3riVTac4BYg2rJBR9hrt8mJt2KMTAEPn7bXob6HhCGTegrCqKM74NYW8XK0zAigAbCdQ4Vpcu+QH5O5YP3j+pnHfM/HLbiqZNEqwADlrD6O62ljrqGieCUb5J+tkewT6CsOKW6w+YbKQ1YCtMWeVfHuvHFvedlzaLthKvzyh7ckd3PWuzluNqZp338HJNLO6eg==":null,
            'isAdmin' =>in_array('admin', user_roles())?True:False,
            'office_start_time' => $attendanceSettings->office_start_time,
            'office_end_time' => $attendanceSettings->office_end_time,
             'outside_clcokin' => ($attendanceSettings->allow_outside_clockin)=="yes" ?true:false,
        ]);
    }
   public function changePassword(Request $request)
    {
      
        if(request()->password == null ||  request()->new_password == null){
          return ApiResponse::make('password and new password is required');
        }
        
        $password = request()->password;
        $new_password = request()->new_password;
        $user = User::with('userAuth')->where('id', api_user()->id)->first();
   
        if (!\Hash::check($password, $user->userAuth->password)) {
            return ApiResponse::make('Old password is incorrect');
        }
    
        if (\Hash::check($new_password, $user->password)) {
            return ApiResponse::make('New password cannot be the same as the old password');
        }
        $user_auth = UserAuth::where('email', api_user()->email)->first();
        $hash = \Hash::make($new_password);
        $user_auth->password = $hash;
        $user_auth->save();
    
        return ApiResponse::make('Password Changed Successfully');
    }
    
     public function changeFcm(Request $request){
        if(request()->fcm_token == null){
            return ApiResponse::make('Device Id is required');
        }
        $user_data = User::where('id',api_user()->id)->first();
        $user_data->fcm_token = request()->fcm_token;
        $user_data->save();
        return ApiResponse::make('Device Id Updated Successfully');
    }

        public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest){
        $user = User::where('email',$forgotPasswordRequest->email)->first();
        if ($user) {
            $otp = mt_rand(1000, 9999);
            PasswordReset::where('email', $forgotPasswordRequest->email)->delete();
            PasswordReset::create([
                'email' => $forgotPasswordRequest->email,
                'token' => $otp,
            ]);
            $user->notify(new ResetPasswordNotification($otp));
            return response()->json(['message' => 'Password reset OTP has been sent to your email.']);
        } else {
            return response()->json(['message' => 'User not found.']);
        }
    }
    public function verifyResetPasswordToken(EmailVerifyRequest $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)
            ->where('verified', false)
            ->first();
    
        if ($passwordReset) {
            $passwordReset->verified = true;
            $passwordReset->save();
            return response()->json(['message' => 'OTP verified successfully.']);
        } else {
            return response()->json(['message' => 'Invalid OTP.']);
        }
    }
    
public function resetPassword(ResetPasswordRequest $request)
{
    $passwordReset = PasswordReset::where('token', $request->token)
            ->where('verified', true)
            ->first();
    if ($passwordReset) {
        $user_auth = UserAuth::where('email', $passwordReset->email)->first();
        $hash = \Hash::make($request->password);
        $user_auth->password = $hash;
        $user_auth->save();
        $passwordReset->delete();
        PersonalAccessToken::where('tokenable_id',$user_auth->id)->delete();
        return response()->json(['message' => 'Password reset successfully.']);
    } else {
        return response()->json(['message' => 'Invalid OTP.'], 400);
    }
}

}
