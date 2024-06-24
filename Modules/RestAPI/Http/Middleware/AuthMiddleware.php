<?php

namespace Modules\RestAPI\Http\Middleware;

use App\Helper\Reply;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\DB;
use Closure;
use App\Models\User;
use Froiden\RestAPI\Exceptions\UnauthorizedException;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
         // Decrypting request
         if($request->input('body')){
         $decryptRequest = decryptRequest($request->input('body'));

         // merge decrypted request
         $request->merge($decryptRequest); 
         }
        
        // Do not apply this middleware to OPTIONS request
       if ($request->getMethod() !== 'OPTIONS') {
            $user = api_user();
            if (!$user) {
                return response()->json(['body'=>encryptRequest( [
                    'status' => 'fail',
                    'status_code' => 401,
                    'message' => AuthMiddleware::getTranslated('User not found')
                ])]);
            }
           $response = $this->validateLoginActiveDisabled($user);
            if ($response !== true) {
                $user = auth()->user();
                // $user->currentAccessToken()->delete();
                return response()->json(['body'=>encryptRequest($response)]);;
            }
        }
        $response = $next($request);
         $responseData =response()->json(['body'=>encryptRequest($response->original)]);

        return $responseData;
    }
    private static function getTranslated($message)
    {
        $trans = trans($message);

        if ($trans == $message) {
            return $message;
        }

        return $trans;

    }
     private static function validateLoginActiveDisabled($userAuth)
    {

        self::restrictUserLoginFromOtherSubdomain($userAuth);

        $globalSetting = GlobalSetting::first();
        $userCompanies = DB::select('Select count(companies.id) as company_count from companies left join users on users.company_id = companies.id where users.email = "' . $userAuth->email . '"');
        $userInactiveCompanies = DB::select('Select count(companies.id) as company_count from companies left join users on users.company_id = companies.id where users.email = "' . $userAuth->email . '" and companies.status = "inactive"');
$userLicenseExpireCompanies = DB::select('
        SELECT COUNT(companies.id) AS company_count
        FROM companies
        LEFT JOIN users ON users.company_id = companies.id
        WHERE users.email = "' . $userAuth->email . '"
        AND (companies.status = "license_expired" OR DATE(companies.licence_expire_on) = CURDATE())
       ');
        if ($globalSetting->company_need_approval) {
            $userUnapprovedCompanies = DB::select('Select count(companies.id) as company_count from companies left join users on users.company_id = companies.id where users.email = "' . $userAuth->email . '" and companies.approved = 0');
            if ($userCompanies[0]->company_count > 0 && $userCompanies[0]->company_count == $userUnapprovedCompanies[0]->company_count) {
                return [
                    'status' => false,
                    'status_code' => 401,
                    'message' => __('auth.failedCompanyUnapproved')
                ];
            }
        }

        if ($userCompanies[0]->company_count > 0 && $userCompanies[0]->company_count == $userInactiveCompanies[0]->company_count) {
            return [
                'status' => false,
                'status_code' => 401,
                'message' => __('auth.companyAccountDisabled')
            ];
        }
        if ($userCompanies[0]->company_count > 0 && $userCompanies[0]->company_count == $userLicenseExpireCompanies[0]->company_count) {
            return [
                'status' => false,
                'status_code' => 401,
                'message' => __('auth.companyAccountDisabled')
            ];
        }

        if ($userAuth->where('status', 'deactive')->count() == $userAuth->count()) {
            return [
                'status' => false,
                'status_code' => 401,
                'message' => __('auth.failedBlocked')
            ];
        }

        if ($userAuth->where('login', 'disable')->count() == $userAuth->count()) {
            return [
                'status' => false,
                'status_code' => 401,
                'message' => __('auth.failedLoginDisabled')
            ];
        }

        return true;
    }
    private static function restrictUserLoginFromOtherSubdomain($userAuth)
    {
        if (!module_enabled('Subdomain')) {
            return true;
        }

        $company = getCompanyBySubDomain();

        // Check if superadmin is trying to login
        if (!$company) {
            $userCount = $userAuth->whereNull('company_id')->count();
        }
        else {
            $userCount = $userAuth->where('company_id', $company->id)->count();
        }

        if (!$userCount) {
            
            return   [
                'status' => false,
                'status_code' => 401,
                'message' => __('auth.failed')
             ];
        }

        return true;
    }
}
