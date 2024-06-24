<?php

namespace App\Http\Controllers\SuperAdmin;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Helper\Reply;
use App\Models\Module;
use GuzzleHttp\Client;
use App\Models\Company;
use App\Models\GlobalSetting;
use App\Models\SuperAdmin\Feature;
use App\Models\SuperAdmin\Package;
use App\Notifications\NewCustomer;
use App\Models\SuperAdmin\FrontFaq;
use Illuminate\Support\Facades\App;
use App\Models\SuperAdmin\SeoDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\SuperAdmin\FooterMenu;
use App\Models\SuperAdmin\FrontDetail;
use App\Models\SuperAdmin\FrontClients;
use App\Models\SuperAdmin\FrontFeature;
use App\Models\SuperAdmin\Testimonials;
use App\Models\SuperAdmin\TrFrontDetail;
use App\Models\SuperAdmin\PackageSetting;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\AccountBaseController;
use App\Notifications\SuperAdmin\ContactUsMail;
use App\Http\Requests\SuperAdmin\ContactUs\ContactUsRequest;
use App\Http\Requests\SuperAdmin\Register\StoreClientRequest;
use App\Models\SuperAdmin\GlobalCurrency;
use App\Models\UserAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ApplyJob;
use App\Models\FamilyDetail;
use App\Models\AcademicQualification;
use App\Models\Reference;
use App\Models\WorkExperience;
use App\Helper\Files;
use App\Models\SuperAdmin\FeatureReadDetail;
use App\Http\Requests\SuperAdmin\EnquiryUs\EnquiryRequest;
use App\Notifications\SuperAdmin\EnquiryUs;
use App\Models\SuperAdmin\Enquiry;
use App\Models\SuperAdmin\Blog;
use Modules\Payroll\Entities\SalaryTds;
use Modules\Payroll\Entities\SalarySlip;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\PayrollSetting;
use App\Models\EmployeeDetails;



class FrontendController extends FrontBaseController
{

    public function index($slug = null)
    {

        if ($this->global->setup_homepage == 'custom') {
            return response(file_get_contents($this->global->custom_homepage_url));
        }

        if ($this->global->setup_homepage == 'signup') {
            return $this->loadSignUpPage();
        }

        if ($this->global->setup_homepage == 'login') {
            return $this->loadLoginPage();
        }

        $this->seoDetail = SeoDetail::where('page_name', 'home')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'home')->first();

        $this->pageTitle = $this->seoDetail ? $this->seoDetail->seo_title : __('app.menu.home');
        $this->currencies = GlobalCurrency::get();
        $this->packages = Package::where('default', 'no')
            ->where('currency_id', global_setting()->currency_id)
            ->where('is_private', 0)
            ->orderBy('sort', 'ASC')
            ->get();

        $localeLanguageId = optional($this->localeLanguage)->id;
        $imageFeaturesCount = Feature::where('language_setting_id', $localeLanguageId)->where('type', 'image')->count();
        $iconFeaturesCount = Feature::where('language_setting_id', $localeLanguageId)->where('type', 'icon')->count();
        $frontClientsCount = FrontClients::select('id', 'language_setting_id')->where('language_setting_id', $localeLanguageId)->count();
        $testimonialsCount = Testimonials::select('id', 'language_setting_id')->where('language_setting_id', $localeLanguageId)->count();

          $this->featureWithImages = Feature::leftJoin('feature_image','feature_image.featureImageId','=','features.id')
            ->leftJoin('feature_read_details', function($join) {
            $join->on('feature_read_details.feature_id', '=', 'features.id')
                ->whereRaw('feature_read_details.id = (select id from feature_read_details where feature_read_details.feature_id = features.id limit 1)');
        })
         ->where([
            'language_setting_id' => $imageFeaturesCount > 0 ? ($localeLanguageId) : null,
            'type' => 'image'
        ])->whereNull('front_feature_id')->select('features.id as featureId','features.title','features.description','features.image','feature_image.*','feature_read_details.feature_id as featureReadMoreId')->get();


        $this->featureWithIcons = Feature::where([
            'language_setting_id' => $iconFeaturesCount > 0 ? ($localeLanguageId) : null,
            'type' => 'icon'
        ])->whereNull('front_feature_id')->get();

        $this->frontClients = FrontClients::where('language_setting_id', $frontClientsCount > 0 ? ($localeLanguageId) : null)->get();
        // $this->testimonials = Testimonials::where('language_setting_id', $testimonialsCount > 0 ? ($localeLanguageId) : null)->get();
        $this->testimonials = Testimonials::leftJoin('companies','testimonials.company_id','=','companies.id')->where('testimonials.language_setting_id', $testimonialsCount > 0 ? ($localeLanguageId) : null)->select('testimonials.*','companies.company_name','companies.logo')->get();
        $this->trialPackage = Package::where('default', 'trial')->first();

        // Check if trail is active
        $this->packageSetting = PackageSetting::where('status', 'active')->first();

        // Multi-Page design
        if ($this->global->front_design == 1) {

            if ($slug) {
                $this->slugData = FooterMenu::where('slug', $slug)->first();
                $this->pageTitle = $this->slugData->name;

                return view('super-admin.saas.footer-page', $this->data);
            }

            return view('super-admin.saas.home', $this->data);
        }

        // Single page design is selected
        $this->packageFeaturesModuleData = Module::where('module_name', '<>', 'settings')
            ->where('module_name', '<>', 'dashboards')
            ->where('module_name', '<>', 'restApi')
            ->whereNotIn('module_name', Module::disabledModuleArray())
            ->get();

        $this->packageFeatures = $this->packageFeaturesModuleData->pluck('module_name')->toArray();
        $this->packageModuleData = $this->packageFeaturesModuleData->pluck('module_name', 'id')->all();

        $this->activeModule = $this->packageFeatures;

        return view('super-admin.front.home', $this->data);

    }

    public function feature()
    {
        App::setLocale($this->locale);
        $this->seoDetail = SeoDetail::where('page_name', 'feature')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'feature')->first();

        $this->pageTitle = isset($this->seoDetail) ? $this->seoDetail->seo_title : __('superadmin.menu.features');
        // $types = ['task', 'bills', 'team', 'apps'];

        // foreach ($types as $type) {
        //     $featureCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => $type])->count();
        //     $this->data['feature' . ucfirst(str_plural($type))] = Feature::where([
        //         'language_setting_id' => $featureCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
        //         'type' => $type
        //     ])->get();
        // }

        // $frontClientsCount = FrontClients::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
        // $this->frontClients = FrontClients::where('language_setting_id', $frontClientsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();
        // $iconFeaturesCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => 'icon'])->count();
        // $this->trialPackage = Package::where('default', 'trial')->first();
        $this->packageSetting = PackageSetting::where('status', 'active')->first();

        // $this->frontFeatures = FrontFeature::with('features')->where([
        //     'language_setting_id' => $iconFeaturesCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
        // ])->get();

        // abort_if($this->setting->front_design != 1, 403);
        
         $localeLanguageId = optional($this->localeLanguage)->id;
        $imageFeaturesCount = Feature::where('language_setting_id', $localeLanguageId)->where('type', 'image')->count();
        $this->featureWithImages = Feature::leftJoin('feature_image', 'feature_image.featureImageId', '=', 'features.id')
        ->leftJoin('feature_read_details', function ($join) {
            $join->on('feature_read_details.feature_id', '=', 'features.id')
                ->whereRaw('feature_read_details.id = (select id from feature_read_details where feature_read_details.feature_id = features.id limit 1)');
        })
        ->where([
            'language_setting_id' => $imageFeaturesCount > 0 ? ($localeLanguageId) : null,
            'type' => 'image'
        ])->whereNull('front_feature_id')->select('features.id as featureId', 'features.title', 'features.description', 'features.image', 'feature_image.*', 'feature_read_details.feature_id as featureReadMoreId')->get();


        return view('super-admin.saas.feature', $this->data);
    }

    public function pricing()
    {
        abort_403($this->setting->front_design != 1);

        App::setLocale($this->locale);
        $this->seoDetail = SeoDetail::where('page_name', 'pricing')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'pricing')->first();
        $this->pageTitle = isset($this->seoDetail) ? $this->seoDetail->seo_title : __('app.menu.pricing');


        $packageCurrencyId = request()->currencyId ?: global_setting()->currency_id;

        $this->packages = Package::where('default', 'no')
            ->where('currency_id', $packageCurrencyId)
            ->where('is_private', 0)
            ->orderBy('sort', 'ASC')
            ->get();

        $this->packageFeaturesModuleData = Module::where('module_name', '<>', 'settings')
            ->where('module_name', '<>', 'dashboards')
            ->where('module_name', '<>', 'restApi')
            ->whereNotIn('module_name', Module::disabledModuleArray())
            ->get();

        $this->packageFeatures = $this->packageFeaturesModuleData->pluck('module_name')->toArray();
        $this->packageModuleData = $this->packageFeaturesModuleData->pluck('module_name', 'id')->all();
        $this->trialPackage = Package::where('default', 'trial')->first();
        $this->packageSetting = PackageSetting::where('status', 'active')->first();

        $this->annualPlan = $this->packages->filter(function ($value) {
            return $value->annual_status == 1;
        })->count();

        $this->monthlyPlan = $this->packages->filter(function ($value) {
            return $value->monthly_status == 1;
        })->count();


        $this->activeModule = $this->packageFeatures;

        if (request()->ajax()) {
            return Reply::dataOnly(
                [
                    'view' => view('super-admin.saas.pricing-plan', $this->data)->render()
                ]
            );
        }

        $frontFaqsCount = FrontFaq::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();

        $this->frontFaqs = FrontFaq::where('language_setting_id', $frontFaqsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();

        // Check if trail is active
        $this->packageSetting = PackageSetting::where('status', 'active')->first();
        $this->trialPackage = Package::where('default', 'trial')->first();

        $this->currencies = GlobalCurrency::get();

        return view('super-admin.saas.pricing', $this->data);
    }

    public function pricingPlan()
    {
        if (request()->ajax()) {
            $packageCurrencyId = request()->currencyId ?: global_setting()->currency_id;

            $this->packages = Package::where('default', 'no')
                ->with('currency')
                ->where('currency_id', $packageCurrencyId)
                ->where('is_private', 0)
                ->orderBy('sort', 'ASC')
                ->get();

            $this->packageFeaturesModuleData = Module::where('module_name', '<>', 'settings')
                ->where('module_name', '<>', 'dashboards')
                ->where('module_name', '<>', 'restApi')
                ->whereNotIn('module_name', Module::disabledModuleArray())
                ->get();

            $this->packageFeatures = $this->packageFeaturesModuleData->pluck('module_name')->toArray();
            $this->packageModuleData = $this->packageFeaturesModuleData->pluck('module_name', 'id')->all();

            $this->activeModule = $this->packageFeatures;

            return Reply::dataOnly(
                [
                    'view' => view('super-admin.front.section.pricing-plan', $this->data)->render()
                ]
            );
        }
    }

    public function contact()
    {
        App::setLocale($this->locale);
        $this->seoDetail = SeoDetail::where('page_name', 'contact')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'contact')->first();
        $this->pageTitle = $this->seoDetail ? $this->seoDetail->seo_title : __('app.menu.contact');
        $this->packageSetting = PackageSetting::where('status', 'active')->first();

        abort_if($this->setting->front_design != 1, 403);

        return view('super-admin.saas.contact', $this->data);
    }

    public function page($slug = null)
    {
        App::setLocale($this->locale);
        $this->slugData = FooterMenu::where('slug', $slug)->where('language_setting_id', $this->localeLanguage->id)->firstOrFail();
        $this->packageSetting = PackageSetting::where('status', 'active')->first();
        $this->seoDetail = SeoDetail::where('page_name', $this->slugData->slug)->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', $this->slugData->slug)->first();
        $this->pageTitle = $this->slugData->name;

        if ($this->setting->front_design == 1) {
            return view('super-admin.saas.footer-page', $this->data);
        }

        return view('super-admin.front.footer-page', $this->data);
    }

    public function contactUs(ContactUsRequest $request)
    {
        $this->recaptchaValidate($request);
        $this->pageTitle = 'superadmin.menu.contact';
        $generatedBys = User::allSuperAdmin();
        $frontDetails = FrontDetail::first();
        $this->table = '<table><tbody style="color:#0000009c;">
        <tr>
            <td><p>Name : </p></td>
            <td><p>' . $request->name . '</p></td>
        </tr>
        <tr>
            <td><p>Email : </p></td>
            <td><p>' . $request->email . '</p></td>
        </tr>
        <tr>
            <td style="font-family: Avenir, Helvetica, sans-serif;box-sizing: border-box;min-width: 98px;vertical-align: super;"><p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Message : </p></td>
            <td><p>' . $request->message . '</p></td>
        </tr>
</tbody>
</table>';

        if ($frontDetails->email) {
            Notification::route('mail', $frontDetails->email)
                ->notify(new ContactUsMail($this->data));

        }
        else {
            Notification::route('mail', $generatedBys)
                ->notify(new ContactUsMail($this->data));
        }


        return Reply::success('Thanks for contacting us. We will catch you soon.');
    }

    public function recaptchaValidate($request)
    {
        $global = global_setting();

        if ($global->google_recaptcha_status == 'active') {
            $gRecaptchaResponseInput = 'g-recaptcha-response';

            $gRecaptchaResponse = $global->google_captcha_version == 'v2' ? $request->{$gRecaptchaResponseInput} : $request->get('recaptcha_token');
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return false;
            }
        }

        return true;
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $setting = GlobalSetting::first();

        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => $setting->google_recaptcha_secret,
                    'response' => $googleRecaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        return $body->success;
    }

    public function changeLanguage($lang)
    {
        // set the language session and redirect back
        session(['language' => $lang]);

        return redirect()->back();
    }

    public function loadSignUpPage()
    {
        if (\user()) {
            return redirect(getDomainSpecificUrl(route('login'), \user()->company));
        }

        $this->seoDetail = SeoDetail::where('page_name', 'home')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'home')->first();
        $this->pageTitle = 'Sign Up';

        $view = ($this->setting->front_design == 1) ? 'super-admin.saas.register' : 'super-admin.front.register';


        if ($this->global->frontend_disable) {
            $view = 'auth.register';
        }

        $this->trFrontDetail = TrFrontDetail::where('language_setting_id', $this->localeLanguage->id)->first();
        $this->trFrontDetail = $this->trFrontDetail ? $this->trFrontDetail : TrFrontDetail::where('language_setting_id', $this->enLocaleLanguage->id)->first();

        $this->registrationStatus = $this->global;

        return view($view, $this->data);
    }

    public function loadLoginPage()
    {
        if (\user()) {
            return redirect(getDomainSpecificUrl(route('login'), \user()->company));
        }

        // if ($this->isLegal()) {
        //     return redirect('verify-purchase');
        // }

        if ($this->global->frontend_disable) {
            return view('auth.login', $this->data);
        }

        if (module_enabled('Subdomain')) {
            $this->pageTitle = __('subdomain::app.core.workspaceTitle');

            $view = ($this->setting->front_design == 1) ? 'subdomain::saas.workspace' : 'subdomain::workspace';

            return view($view, $this->data);
        }

        if ($this->setting->front_design == 1 && $this->setting->login_ui == 1) {
            return view('super-admin.saas.login', $this->data);
        }

        $this->pageTitle = 'Login Page';

        return view('auth.login', $this->data);
    }

    public function clientSignup(Company $company)
    {
        $this->company = $company;

        return view('super-admin.front.client-signup', $this->data);
    }

    public function clientRegister(StoreClientRequest $request, Company $company)
    {
        DB::beginTransaction();

        $userAuth = UserAuth::createUserAuthCredentials($request->email, $request->password);

        $user = User::create([
            'company_id' => $company->id,
            'name' => $request->name,
            'email' => $request->email,
            'admin_approval' => !$company->admin_client_signup_approval,
            'user_auth_id' => $userAuth->id
        ]);

        $user->clientDetails()->create(['company_name' => $request->company_name]);

        $role = Role::where('company_id', $company->id)->where('name', 'client')->select('id')->first();
        $user->attachRole($role->id);

        $user->assignUserRolePermission($role->id);

        $log = new AccountBaseController();

        // Log search
        $log->logSearchEntry($user->id, $user->name, 'clients.show', 'client');

        if (!is_null($user->email)) {
            $log->logSearchEntry($user->id, $user->email, 'clients.show', 'client');
        }

        if (!is_null($user->clientDetails->company_name)) {
            $log->logSearchEntry($user->id, $user->clientDetails->company_name, 'clients.show', 'client');
        }

        Notification::send(User::allAdmins($user->company->id), new NewCustomer($user));

        session(['company' => $company]);
        session(['user' => $user]);

        // login user
        Auth::login($userAuth, true);
        DB::commit();

        return Reply::redirect(route('dashboard'), __('superadmin.clientRegistrationSuccess'));
    }
    
     public function apply($id)
    {
        $this->logo=Company::where('id',$id)->first();
        App::setLocale($this->locale);
        $this->seoDetail = SeoDetail::where('page_name', 'contact')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'contact')->first();
        $this->pageTitle = 'Interview Application';

        abort_if($this->setting->front_design != 1, 403);

        return view('super-admin.saas.apply',$this->data);
    }

    public function submitApply(Request $request)
    {
          
                   $this->pageTitle = 'Interview Application';
                        if (request()->hasFile('cv')) {
                            $check=Files::deleteFile($request->cv, 'cv');
                            $cv= Files::uploadLocalOrS3(request()->cv, 'cv');
                        }
                   $GetId=DB::table('apply_job')->insertGetId([
                      'company_id'=>$request->company_id,
                        'name' => $request->name,
                        'fName' => $request->fName,
                        'email' => $request->email,
                        'mobile' => $request->phone_number,
                        'address' => $request->address,
                        'file' => $cv??null,
                        'applied_position' => $request->applied_position,
                        'technology'=>$request->technology,
                        'gender'=>$request->gender,
                        'dob'=> Carbon::createFromFormat('d-m-Y', $request->dob),
                        'pan'=>$request->pan,
                        'blood_group'=>$request->blood_group,
                        'martial_s'=>$request->martial_s,
                        'passport'=>$request->passport,
                        'passport_number'=>$request->passport_number,
                        'residential_address'=>$request->residential,
                        'referred_by'=>$request->referredBy
                    ]);
                
                    foreach ($request->mobile as $key => $value) {
                         if(isset($request->mobile[$key])){
                            FamilyDetail::create([
                                'candidate_id'=>$GetId,
                                'name' =>$request->nameQ[$key],
                                'relation' => $request->relation[$key],
                                'mobile' => $request->mobile[$key],
                                'profession' =>$request->profession[$key],
                                'is_dependent' => $request->dependent[$key], // Convert to uppercase
                            ]);
                    }
                } 
              
                 
                foreach ($request->qualification as $key => $value) {
                     if(isset($request->percentage[$key])){
                        AcademicQualification::create([
                            'candidate_id'=>$GetId,
                            'course' => $request->qualification[$key],
                            'board' => $request->board[$key],
                            'passing_year' => $request->passingY[$key],
                            'percentage' => $request->percentage[$key],
                        ]);
                    }
                }
               
                    foreach ($request->organization as $key => $value) {
                         if(isset($request->organization[$key])){
                        WorkExperience::create([
                            'candidate_id'=>$GetId,
                            'organization_name' =>$request->organization[$key],
                            'starting_salary' => $request->salary[$key],
                            'last_salary' => $request->lastDrawn[$key],
                            'duration_from' =>  Carbon::createFromFormat('d-m-Y', $request->From[$key]),
                            'duration_to' =>  Carbon::createFromFormat('d-m-Y', $request->to[$key]), // Convert to uppercase
                            'reason_leave' =>$request->rLeave[$key], // Convert to uppercase

                        ]);
                    }
                }
                    foreach ($request->company as $key => $value) {
                        if(isset($request->company[$key])){
                        Reference::create([
                            'candidate_id'=>$GetId,
                            'company_name' => $request->company[$key],
                            'reporting_person' =>$request->rPerson[$key],
                            'designation' =>$request->designation[$key],
                            'mobile_no' => $request->mobileR[$key],
                            'reportees' => $request->reportees[$key], // Convert to uppercase

                        ]);
                    }
                    }
                    $this->pageTitle = 'Job Application';
                    $generatedBys = User::allSuperAdmin();
                    $frontDetails = FrontDetail::first();
                     return Reply::success('Thank you for completing the interview form. We appreciate your time and effort. We will review your submission and be in touch shortly regarding the next steps in the hiring process.');
                    }
                    
                    function readMore(Request $request){
                         App::setLocale($this->locale);
                        $localeLanguageId = optional($this->localeLanguage)->id;

                         $this->seoDetail = SeoDetail::where('page_name', 'feature')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'feature')->first();
                         $frontClientsCount = FrontClients::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
                         $this->frontClients = FrontClients::where('language_setting_id', $frontClientsCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->get();
                         $iconFeaturesCount = Feature::select('id', 'language_setting_id', 'type')->where(['language_setting_id' => $this->localeLanguage ? $this->localeLanguage->id : null, 'type' => 'icon'])->count();        $iconFeaturesCount = Feature::where('language_setting_id', $localeLanguageId)->where('type', 'icon')->count();

                         $this->frontFeatures = FrontFeature::with('features')->where([
                        'language_setting_id' => $iconFeaturesCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null,
                    ])->get();
                                $this->pageTitle = "Read More";
                        $this->packageSetting = PackageSetting::where('status', 'active')->first();
                    $this->details=FeatureReadDetail::with('featureDetail')->where('feature_id',$request->id)->get();
                        return view('super-admin.saas.feature-read-more', $this->data);
                   }
                   
                      public function enquiry()
                   {
                       App::setLocale($this->locale);
                       $this->seoDetail = SeoDetail::where('page_name', 'contact')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'contact')->first();
                       $this->pageTitle = $this->seoDetail ? $this->seoDetail->seo_title : __('app.menu.contact');
                       $this->packageSetting = PackageSetting::where('status', 'active')->first();
               
                       abort_if($this->setting->front_design != 1, 403);
               
                       return view('super-admin.saas.enquiry', $this->data);
                   }

                    public function enquiryUs(EnquiryRequest $request)
    {
        $this->recaptchaValidate($request);
        $this->pageTitle = 'superadmin.menu.contact';
        $generatedBys = User::allSuperAdmin();
        $frontDetails = FrontDetail::first();
    $checkEmail=Enquiry::where('email', $request->email_h??null)->first();
        if($checkEmail){
            return response()->json(['error'=>'Email already registered']);
        }else{
        $enquiry = new Enquiry();
        $enquiry->name = $request->name ?? $request->name_h;
        $enquiry->company_name = $request->company_name ?? $request->company_name_h;
        $enquiry->mobile = $request->mobile ?? $request->mobile_h;
        $enquiry->email = $request->email ?? $request->email_h;
        $enquiry->company_size = $request->company_size ?? $request->company_size_h;
        $enquiry->save();

        $name = ucfirst($request->name ?? $request->name_h);
        $mail = $request->email ?? $request->email_h;
        $company_name = $request->company_name ?? $request->company_name_h;
        $mobile = $request->mobile ?? $request->mobile_h;
        $company_size = $request->company_size ?? $request->company_size_h;
        $this->table = '<table><tbody style="color:#0000009c;">
                       <tr>
                           <td><p>Name : </p></td>
                           <td><p>' . $name . '</p></td>
                       </tr>
                       <tr>
                           <td><p>Email : </p></td>
                           <td><p>' . $mail . '</p></td>
                       </tr>
                       <tr>
                           <td><p>Company name : </p></td>
                           <td><p>' . $company_name . '</p></td>
                       </tr>
                       <tr>
                       <td><p>Mobile : </p></td>
                       <td><p>' . $mobile . '</p></td>
                   </tr>
                   <tr>
                       <td><p>Company Size : </p></td>
                       <td><p>' . $company_size . '</p></td>
                   </tr>
               </tbody></table>';
        $this->pdfPath = asset('pdf/Vetanwala.pdf');

        $this->revertMailData = "<p> Dear $company_name ,</p>
               <p>Greetings and salutations from Vetanwala team.</p>
          <p>As a valued client, we are committed to providing you with a cutting-edge HR solution that will transform the way you manage your workforce. Our team will get in touch with you to arrange a free demo ASAP, to get you started, we've put up a thorough presentation that highlights all of Vetanwala's features, advantages, and capabilities. This presentation acts as a helpful manual to assist you in navigating and making the most of our system.</p>
          <p>Best regards,</p>
          <p>Vetanwala Team</p>
          <p>4th floor Salarpuria Towers-01, Hosur Road,<br>
          Kormangala, Bengaluru - 560095, KA
          </p>";


        if ($mail) {
            Notification::route('mail', $mail)
                ->notify(new ContactUsMail($this->data,$this->pdfPath));
        }

        if ($frontDetails->email) {
            Notification::route('mail', $frontDetails->email)
                ->notify(new EnquiryUs($this->data));

        } else {
            Notification::route('mail', $generatedBys)
                ->notify(new EnquiryUs($this->data));
        }


        return Reply::success('Thanks for Enquiry us. We will catch you soon.');
        }
    }
  public function blog(){
        App::setLocale($this->locale);
        $this->seoDetail = SeoDetail::where('page_name', 'contact')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'contact')->first();
        $this->pageTitle = 'Blog';
        $this->packageSetting = PackageSetting::where('status', 'active')->first();
        $this->blog=Blog::paginate(10);
        return view('super-admin.saas.blog', $this->data);
   }
    
    public function singleBlog($id){
        App::setLocale($this->locale);
        $this->seoDetail = SeoDetail::where('page_name', 'contact')->where('language_setting_id', $this->localeLanguage?->id)->first() ?: SeoDetail::where('page_name', 'contact')->first();
        $this->pageTitle ="Blog Details";
        $this->packageSetting = PackageSetting::where('status', 'active')->first();
        $this->singleBlog=Blog::where('id',$id)->first();
        $this->blog = Blog::where('id', '!=', $id)->limit(10)->get();
        return view('super-admin.saas.single-blog', $this->data);
    }
    
      public function downloadPdfMobile($id)
    {
        $this->salarySlip = SalarySlip::with('user', 'user.employeeDetail', 'salary_group', 'salary_payment_method')->whereRaw('md5(id) = ?', $id)->firstOrFail();
        $this->payrollSetting = PayrollSetting::with('currency')->first();
            $salaryJson = json_decode($this->salarySlip->salary_json, true);
            $this->earnings = $salaryJson['earnings'];
            $this->deductions = $salaryJson['deductions'];
            $extraJson = json_decode($this->salarySlip->extra_json, true);

            if($this->salarySlip->payroll_cycle->cycle == 'monthly'){
                $this->basicSalary = $this->salarySlip->basic_salary;
            }
            elseif($this->salarySlip->payroll_cycle->cycle == 'weekly'){
                $this->basicSalary = $this->salarySlip->basic_salary / 4;
            }
            elseif($this->salarySlip->payroll_cycle->cycle == 'semimonthly'){
                $this->basicSalary = $this->salarySlip->basic_salary / 2;
            }
            elseif($this->salarySlip->payroll_cycle->cycle == 'biweekly'){
                $perday = $this->salarySlip->basic_salary / 30;
                $this->basicSalary = $perday * 14;
            }

            if (!is_null($extraJson)) {
                $this->earningsExtra = $extraJson['earnings'];
                $this->deductionsExtra = $extraJson['deductions'];
            }
            else {
                $this->earningsExtra = '';
                $this->deductionsExtra = '';
            }

            if ($this->earningsExtra == '') {
                $this->earningsExtra = array();
            }

            if ($this->deductionsExtra == '') {
                $this->deductionsExtra = array();
            }

            $earn = [];
            $extraEarn = [];

            foreach($this->earnings as $key => $value){
                if($key != 'Total Hours')
                {
                    $earn[] = $value;
                }
            }

            foreach($this->earningsExtra as $key => $value){
                if($key != 'Total Hours')
                {
                    $extraEarn[] = $value;
                }
            }

            $earn = array_sum($earn);

            $extraEarn = array_sum($extraEarn);

            if($this->basicSalary == '' || is_null($this->basicSalary)){
                $this->basicSalary = 0.0;
            }

            $this->fixedAllowance = $this->salarySlip->gross_salary - ($this->basicSalary + $earn + $extraEarn);

            $this->fixedAllowance = ($this->fixedAllowance < 0) ? 0 : round(floatval($this->fixedAllowance), 2);

            $this->payrollSetting = PayrollSetting::first();

            $this->extraFields = [];

            if ($this->payrollSetting->extra_fields) {
                $this->extraFields = json_decode($this->payrollSetting->extra_fields);
            }

            $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->salarySlip->user->id)->first()->withCustomFields();
            $this->company = $this->employeeDetail->company;
            $this->currency = PayrollSetting::with('currency')->first();

            if (!is_null($this->employeeDetail) && $this->employeeDetail->getCustomFieldGroupsWithFields()) {
                $this->fieldsData = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
                $this->fields = $this->fieldsData->filter(function ($value, $key) {
                    return in_array($value->id, $this->extraFields);
                })->all();
            }

            $pdf = app('dompdf.wrapper');
            $pdf->setOption('enable_php', true);
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            $month = Carbon::createFromFormat('m', $this->salarySlip->month)->translatedFormat('F');
            $pdf->loadView('payroll::payroll.pdfview', $this->data);
            $filename = $this->salarySlip->user->employeeDetail->employee_id . '-' . $month . '-' . $this->salarySlip->year;

            return $pdf->download($filename . '.pdf');
      
    }
  
    
}
