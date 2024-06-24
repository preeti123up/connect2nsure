<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Events\NewUserEvent;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Register\StoreRequest;
use App\Models\Company;
use App\Models\EmployeeDetails;
use App\Models\GlobalSetting;
use App\Models\Role;
use App\Models\SuperAdmin\SeoDetail;
use App\Models\SuperAdmin\SignUpSetting;
use App\Models\SuperAdmin\TrFrontDetail;
use App\Models\UniversalSearch;
use App\Models\User;
use App\Models\UserAuth;
use App\Notifications\NewUser;
use App\Scopes\ActiveScope;
use App\Scopes\CompanyScope;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class CompanyRegisterController extends FrontBaseController
{

    public function index()
    {
        $this->global = GlobalSetting::first();

        if (\user()) {
            return redirect(getDomainSpecificUrl(route('login'), \user()->company));
        }

        $this->seoDetail = SeoDetail::where('page_name', 'home')->first();
        $this->pageTitle = 'Sign Up';

        $view = ($this->setting->front_design == 1) ? 'super-admin.saas.register' : 'super-admin.front.register';


        if ($this->global->frontend_disable || $this->global->setup_homepage == 'custom') {
            $view = 'super-admin.register';
        }

        $this->trFrontDetail = TrFrontDetail::where('language_setting_id', $this->localeLanguage->id)->first();
        $this->trFrontDetail = $this->trFrontDetail ?: TrFrontDetail::where('language_setting_id', $this->enLocaleLanguage->id)->first();

        $signUpCount = SignUpSetting::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
        $this->signUpMessage = SignUpSetting::where('language_setting_id', $signUpCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->first();

        $this->registrationStatus = $this->global;

        return view($view, $this->data);
    }

    public function store(StoreRequest $request)
    {
        $company = new Company();
        $global = GlobalSetting::first();

        if ($global->google_recaptcha_v2_status == 'active' && !$this->recaptchaValidate($request)) {
            return Reply::error('Recaptcha not validated.');
        }
     

        DB::beginTransaction();
        try {
            $generateAES_KEY= '!@#$' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 16) . 'VetanWala*&^';
            $company->company_name = $request->company_name;
            $company->company_email = $request->email;
            $company->address = $request->company_name;
            $company->app_name = $request->company_name;
            $company->company_phone = $request->company_phone;
            $company->AES_KEY= $generateAES_KEY;
          

            if (module_enabled('Subdomain')) {
                $company->sub_domain = $request->sub_domain;
            }

            $company->save();

            $user = $this->addUser($company, $request, $global);
            $this->addFaceAdmin($company, $request);

            DB::commit();

            if (!$global->company_need_approval) {
                if (!module_enabled('Subdomain')){
                    Auth::loginUsingId($user->user_auth_id);
                }

            } else {
                session()->flash('company_approval_pending', __('auth.failedCompanyUnapproved'));
                return Reply::redirect(route('front.signup.index'));
            }

        } catch (TransportException $e) {
            DB::rollback();

            return Reply::error('Please contact administrator to set SMTP details to add company', 'smtp_error');
        } catch (\Exception $e) {
            DB::rollback();

            return Reply::error('Some error occurred when inserting the data. Please try again or contact support: ' . $e->getMessage());
        }

        return Reply::redirect(getDomainSpecificUrl(route('login'), $company), __('superadmin.signUpThankYou'));
    }

    public function getEmailVerification($code)
    {
        $this->pageTitle = 'modules.accountSettings.emailVerification';
        $this->message = User::emailVerify($code);

        return view('auth.email-verification', $this->data);
    }

    public function addUser($company, $request, $global)
    {
        // Save Admin
        $user = User::withoutGlobalScopes([CompanyScope::class, ActiveScope::class])
            ->where('company_id', $company->id)
            ->where('email', $request->email)
            ->first();

        if (is_null($user)) {
            $user = new User();
        }

        $userAuth = UserAuth::createUserAuthCredentials($request->email);

        $user->company_id = $company->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 'active';
        $user->user_auth_id = $userAuth->id;
        $user->locale = $company->locale;
        $user->save();

        if ($global->email_verification) {
            $userAuth->sendEmailVerificationNotification();
        }

        if ($request->password != '') {
            UserAuth::where('id', $user->user_auth_id)->update(['password' => bcrypt($request->password)]);
            $user->notify(new NewUser($user, $request->password));
        }

        if (!$user->hasRole('admin')) {

            // Attach Admin Role
            $adminRole = Role::withoutGlobalScope(CompanyScope::class)->where('name', 'admin')->where('company_id', $company->id)->first();

            $employeeRole = Role::withoutGlobalScope(CompanyScope::class)->where('name', 'employee')->where('company_id', $user->company_id)->first();

            $user->roles()->attach($adminRole->id);
            $this->addEmployeeDetails($user, $employeeRole, $company->id);

            $user->assignUserRolePermission($adminRole->id);

        }

        return $user;
    }

    private function addEmployeeDetails($user, $employeeRole, $companyId)
    {
        $employee = new EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->company_id = $companyId;
        /* @phpstan-ignore-line */
        $employee->employee_id = 'EMP-1';
        /* @phpstan-ignore-line */
        $employee->save();

        $search = new UniversalSearch();
        $search->searchable_id = $user->id;
        $search->company_id = $companyId;
        $search->title = $user->name;
        $search->route_name = 'employees.show';
        $search->save();

        // Assign Role
        $user->roles()->attach($employeeRole->id);
        /* @phpstan-ignore-line */
    }

    public function recaptchaValidate($request)
    {
        $global = global_setting();

        if ($global->google_recaptcha_status == 'active') {
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};

            $gRecaptchaResponse = $global->google_recaptcha_v2_status == 'active' ? $gRecaptchaResponse : $request->g_recaptcha;

            if (is_null($gRecaptchaResponse)) {
                return $this->googleRecaptchaMessage();
            }

            $secret = $global->google_recaptcha_v2_status == 'active' ? $global->google_recaptcha_v2_secret_key : $global->google_recaptcha_v3_secret_key;

            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse, $secret);

            if (!$validateRecaptcha) {
                return $this->googleRecaptchaMessage();
            }
        }

        return true;
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse, $secret)
    {
        $client = new Client();

        $googleRecaptchaResponse = is_null($googleRecaptchaResponse) ? '' : $googleRecaptchaResponse;

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => $secret,
                    'response' => $googleRecaptchaResponse
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());

        return $body->success;
    }

    public function googleRecaptchaMessage()
    {
        throw ValidationException::withMessages([
            'g-recaptcha-response' => [__('auth.recaptchaFailed')],
        ]);
    }
    
       public function captcha($tmp)
{
    $phrase = new PhraseBuilder;
    $code = $phrase->build(4, '0123456789'); // Use only numbers for the captcha code
    $builder = new CaptchaBuilder($code, $phrase);
    $builder->setBackgroundColor(220, 210, 230);
    $builder->setMaxAngle(10);
    $builder->setMaxBehindLines(0);
    $builder->setMaxFrontLines(0);
    $builder->build($width = 140, $height = 40, $font = null); // Increase width to 200
    $phrase = $builder->getPhrase();

    if (Session::has('default_captcha_code')) {
        Session::forget('default_captcha_code');
    }
    Session::put('default_captcha_code', $phrase);
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-Type:image/jpeg");
    $builder->output();
}

function generateUniqueEmail($baseEmail) {
    $email = $baseEmail;

    if (!User::where('email', $email)->exists()) {
        return $email; // If email does not exist, return the same email
    }

    $parts = explode('@', $baseEmail);
    $username = $parts[0];
    $domain = $parts[1];
    $counter = 1;

    while (User::where('email', $email)->exists()) {
        $email = $username . str_pad($counter, 2, '0', STR_PAD_LEFT) . '@' . $domain;
        $counter++;
    }

    return $email;
}

public function addFaceAdmin($company, $request,$branch_id = NULL) {
    $email = $this->generateUniqueEmail($request->email);
    $user = new User();
    $userAuth = UserAuth::createUserAuthCredentials($email);
    $user->company_id = $company->id;
    $user->email = $email;
    $user->branch_id = $branch_id;
    $user->status = 'active';
    $user->user_auth_id = $userAuth->id;
    $user->branch_login = $this->generateBranchId();
    $user->default_branch = True;
    $user->save();
}
public function generateBranchId() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    $length = 6; 
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    $shuffledString = str_shuffle($randomString);
    $branchId = 'BR' . $shuffledString;
    return $branchId;
}

}
