<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use App\Models\Team;
use App\Models\Designation;
use App\Models\UserAuth;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\EmployeeDetails;
use App\Models\UniversalSearch;
use Illuminate\Support\Facades\DB;
use App\Traits\UniversalSearchTrait;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Events\NewUserEvent;

class ImportEmployeeJob implements ShouldQueue, ShouldBeUnique
{

    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UniversalSearchTrait;

    private $row;
    private $columns;
    private $company;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row, $columns, $company = null)
    {
        $this->row = $row;
        $this->columns = $columns;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty(array_keys($this->columns, 'name')) && !empty(array_keys($this->columns, 'email')) && filter_var($this->row[array_keys($this->columns, 'email')[0]], FILTER_VALIDATE_EMAIL)) {

            if (!checkCompanyCanAddMoreEmployees($this->company?->id)) {
                $this->job->fail(__('superadmin.updatePlanNote'));
                return;
            }

            $user = User::where('email', trim($this->row[array_keys($this->columns, 'email')[0]]))->first();

            if ($user) {
                $this->job->fail(__('messages.duplicateEntryForEmail') . trim($this->row[array_keys($this->columns, 'email')[0]]));
                return;
            }

            $user = User::where('mobile', trim($this->row[array_keys($this->columns, 'mobile')[0]]))->first();

            if ($user) {
                $this->job->fail(__('Duplicate Enter For Mobile') . trim($this->row[array_keys($this->columns, 'mobile')[0]]));
            }

            $employeeDetails = EmployeeDetails::where('employee_id', trim($this->row[array_keys($this->columns, 'employee_id')[0]]))->first();

            if ($employeeDetails) {
                $this->job->fail(__('messages.duplicateEntryForEmployeeId') . trim($this->row[array_keys($this->columns, 'employee_id')[0]]));
            }

            else {
                DB::beginTransaction();
                try {
                    $user = new User();
                    $user->company_id = $this->company?->id;
                    $user->name = trim($this->row[array_keys($this->columns, 'name')[0]]);
                    $user->email = trim($this->row[array_keys($this->columns, 'email')[0]]);

                    if (isWorksuite())
                    {
                        $user->password = bcrypt(12345678);
                    }

                    $user->mobile = !empty(array_keys($this->columns, 'mobile')) ? trim($this->row[array_keys($this->columns, 'mobile')[0]]) : null;
                    $user->gender = !empty(array_keys($this->columns, 'gender')) ? strtolower(trim($this->row[array_keys($this->columns, 'gender')[0]])) : null;

                    if (isWorksuiteSaas())
                    {
                        $userAuth = UserAuth::createUserAuthCredentials(trim($this->row[array_keys($this->columns, 'email')[0]]), 12345678);
                        $user->user_auth_id = $userAuth->id;
                    }
                    
                    $user->country_id = 99;
                    $user->country_phonecode = 91;

                    $user->save();

                    $getTeam = Team::where([
                        'company_id' => $this->company ? $this->company->id : null,
                        'team_name' => trim($this->row[array_keys($this->columns, 'department_name')[0]])
                    ])->first();


                    $getDesig = Designation::where([
                        'company_id' => $this->company ? $this->company->id : null,
                        'name' =>trim($this->row[array_keys($this->columns, 'designation_name')[0]])
                    ])->first();
                    
                       $getReportingPerson=User::where([
                        'company_id' => $this->company ? $this->company->id : null,
                        'name' =>trim($this->row[array_keys($this->columns, 'reporting_to')[0]])
                    ])->first();

                    if ($user->id) {

                         $carbonDate = trim($this->row[array_keys($this->columns, 'joining_date')[0]]);
                        $carbonDate2 = trim($this->row[array_keys($this->columns, 'date_of_birth')[0]]);
                    

                        $employee = new EmployeeDetails();
                        $employee->company_id = $this->company?->id;
                        $employee->user_id = $user->id;
                        $employee->address = !empty(array_keys($this->columns, 'address')) ? trim($this->row[array_keys($this->columns, 'address')[0]]) : null;
                        $employee->employee_id = !empty(array_keys($this->columns, 'employee_id')) ? trim($this->row[array_keys($this->columns, 'employee_id')[0]]) : (EmployeeDetails::max('id') + 1);
                      //  $employee->joining_date = !empty(array_keys($this->columns, 'joining_date')) ? Carbon::createFromFormat('Y-m-d', $this->row[array_keys($this->columns, 'joining_date')[0]]) : null;
                        $employee->joining_date = !empty(array_keys($this->columns, 'joining_date')) ? $carbonDate : null;
                        $employee->hourly_rate = !empty(array_keys($this->columns, 'hourly_rate')) ? preg_replace('/[^0-9.]/', '', $this->row[array_keys($this->columns, 'hourly_rate')[0]]) : null;
                        $employee->department_id =  $getTeam->id ?  $getTeam->id : null;
                        $employee->designation_id = $getDesig->id ? $getDesig->id: null;
                        $employee->reporting_to = $getReportingPerson->id ? $getReportingPerson->id: null;
                        $employee->date_of_birth =  !empty(array_keys($this->columns, 'date_of_birth')) ? $carbonDate2 : null;
                        $employee->save();
                    }
                
                    //  event(new NewUserEvent($user,12345678));
                    

                    $employeeRole = Role::where('name', 'employee')->first();
                    $user->attachRole($employeeRole);
                    $user->assignUserRolePermission($employeeRole->id);
                    $this->logSearchEntry($user->id, $user->name, 'employees.show', 'employee');
                    DB::commit();
                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                    DB::rollBack();
                    $this->job->fail(__('messages.invalidDate') . json_encode($this->row, true));
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->job->fail($e->getMessage());
                }
            }
        }
        else {
            $this->job->fail(__('messages.invalidData') . json_encode($this->row, true));
        }
    }

}
