<?php

namespace App\Jobs;



use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use App\Traits\UniversalSearchTrait;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CompanyRolePermissionMaster;
use App\Models\CompanyUserPermissionMaster;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportDepartmentJob implements ShouldQueue, ShouldBeUnique
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

        if (!empty(array_keys($this->columns, 'department_name'))) {

            if(trim($this->row[array_keys($this->columns, 'department_name')[0]])=='')
            {

                $this->job->fail(__(' Department Required') . trim($this->row[array_keys($this->columns, 'department_name')[0]]));

            }else{
            $team = Team::where(['team_name'=>trim($this->row[array_keys($this->columns, 'department_name')[0]]),'company_id'=>$this->company?->id])->first();

            if ($team) {
                $this->job->fail(__('Duplicate Entry For Department Name ') . trim($this->row[array_keys($this->columns, 'department_name')[0]]));
             }
            else {
                try {
                       $team   = new Team();
                       $team->team_name = trim($this->row[array_keys($this->columns, 'department_name')[0]]);
                       $team->company_id  = $this->company?->id;
                       $team->save();
                } catch (\Carbon\Exceptions\InvalidFormatException $e) {

                    $this->job->fail(__('messages.invalidDate') . json_encode($this->row, true));
                } catch (\Exception $e) {

                    $this->job->fail($e->getMessage());
                }
            }
          }
        }
        else {
            $this->job->fail(__('messages.invalidData') . json_encode($this->row, true));
        }

    }

}
