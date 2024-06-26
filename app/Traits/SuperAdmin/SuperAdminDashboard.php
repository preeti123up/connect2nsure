<?php

namespace App\Traits\SuperAdmin;

use App\Models\Company;
use App\Models\SuperAdmin\Package;
use App\Scopes\ActiveScope;
use Carbon\Carbon;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;

trait SuperAdminDashboard
{

    use AppBoot;

    /**
     * @return View|Factory
     */
    public function superAdminDashboard()
    {
        $this->isCheckScript();
        $this->pageTitle = 'superadmin.superAdminDashboard';
        $this->recentRegisteredCompanies = Company::with('package')->latest()->limit(5)->get();
        $this->recentSubscriptions = Company::with('package')->where('status', 'active')->whereNotNull('subscription_updated_at')->latest('subscription_updated_at')->limit(5)->get();

        $this->recentLicenceExpiredCompanies = Company::with('package')->where('status', 'license_expired')->where(function ($query) {
            $query->where('licence_expire_on', '<', now()->format('Y-m-d'))
                ->orWhere('licence_expire_on', '=', null);
        })->latest('license_updated_at')
            ->limit(5)
            ->get();

        $this->totalCompanies = Company::withoutGlobalScope(ActiveScope::class)->count();

        $this->activeCompanies = Company::where('status', '=', 'active')->count();
        $this->inactiveCompanies = Company::where('status', '=', 'inactive')->count();

        $this->expiredCompanies = Company::with('package')->where('status', 'license_expired')->where(function ($query) {
            $query->where('licence_expire_on', '<', now()->format('Y-m-d'))
                ->orWhere('licence_expire_on', '=', null);
        })->count();

        $this->topCompaniesUserCount = Company::active()->withCount(['users', 'employees', 'clients'])->orderBy('users_count', 'desc')->limit(5)->get();

        $this->packageCompanyCount = Package::where('default', '!=', 'trial')->withCount(['companies'])->orderBy('companies_count', 'desc')->limit(10)->get();
        $this->totalPackages = Package::where('default', '!=', 'trial')->count();
        $year = now(global_setting()->timezone)->year;

        if (request()->year != '') {
            $year = request()->year;
        }

        $this->registrationsChart = $this->registrationsChart($year);

        return view('super-admin.dashboard.index', $this->data);
    }

    public function registrationsChart($year)
    {
        $companies = Company::whereYear('created_at', $year)->orderBy('created_at');
        $companies = $companies->groupBy('year', 'month')
            ->get([
                DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month,MONTH(created_at) month_number'),
                DB::raw('count(id) as total')
            ]);

        $data['labels'] = $this->convertMonthToName($companies->pluck('month_number')->toArray());
        $data['values'] = $companies->pluck('total')->toArray();
        $data['colors'] = [$this->appTheme->header_color];
        $data['name'] = __('superadmin.dashboard.registrationsChart');

        return $data;
    }

    private function convertMonthToName($toArray)
    {
        $labels = [];
        foreach ($toArray as $month) {
            $labels[] = now()->month($month)->translatedFormat('F');
        }

        return $labels;
    }

}
