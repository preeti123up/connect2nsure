
<div id="leave-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize p-20">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="heading-h1 text-center">Candidate  Details</h3>
                        </div>
                    </div>
                </div>
<div>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">

            <div class="col-lg-12">
                   <div class="row mt-3 border-bottom-grey">
                    <div class="col-md-4">
                    <p><strong>Applied For:</strong> {{ $interview->applied_position ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Technology:</strong> {{ $interview->technology ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">

                    <p><strong>Name:</strong> {{ $interview->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Father Name:</strong> {{ $interview->fName ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Contact Number:</strong> {{ $interview->mobile  ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Email:</strong> {{ $interview->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
<p><strong>Date of Birth:</strong> {{ $interview->dob ? \Carbon\Carbon::parse($interview->dob)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Pan:</strong> {{  $interview->pan  ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Gender:</strong> {{  $interview->gender  ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Martial Status:</strong> {{  $interview->martial_s  ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Passport:</strong> {{  $interview->passport  ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                    <p><strong>Passport No:</strong> {{  $interview->passport_number  ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                        <p><strong>Residential Address	:</strong> {{  $interview->residential_address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                        <p><strong>Permanent Address	:</strong> {{  $interview->address  ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                        <p><strong>Referred By	:</strong> {{  $interview->referred_by  ?? 'N/A' }}</p>
                        </div>
                   </div>
         <!-- Right side with the table -->
         <div class="col-md-12 mt-3 p-0">
                        <h3 class="text-center mb-3">Family Details</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>Mobile</th>
                                <th>Profession</th>
                                <th>Is Dependent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($interview->familyDetails))
                            @foreach($interview->familyDetails as $key => $asset)
                                <tr>
                                    <td>{{ (int)$key + 1 }}</td>
                                    <td>{{ $asset->name ?? 'N/A' }}</td>
                                    <td>{{ $asset->relation ?? 'N/A' }}</td>
                                    <td>{{$asset->mobile  ?? 'N/A' }}</td>
                                    <td>{{$asset->profession  ?? 'N/A' }}</td>
                                    <td>{{$asset->is_dependent  ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- //Qualification -->

                    <div class="mt-5">
                        <h3 class="text-center mb-3">Academic & Professional Qualification</h3>
                  <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Degree/Diploma/Others</th>
                                <th>Board/University</th>
                                <th>Year of Passing </th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($interview->qualification))
                            @foreach($interview->qualification as $key => $asset)
                                <tr>
                                    <td>{{ (int)$key + 1 }}</td>
                                    <td>{{ $asset->course ?? 'N/A' }}</td>
                                    <td>{{ $asset->board ?? 'N/A' }}</td>
                                    <td>{{$asset->passing_year  ?? 'N/A' }}</td>
                                    <td>{{$asset->percentage  ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
                    <!-- end -->

                         <!-- //workExperience -->

                         <div class="mt-5">
    <h3 class="text-center mb-3">Work Experience</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name of Organization</th>
                <th colspan='2' class="text-center">Salary Per Month</th>
                <th colspan='2'>Tenure/Duration</th>
                <th>Reason to Leave</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>Salary(At the time of Joining)</th>
                <th>Last Drawn Salary</th>
                <th>From</th>
                <th>To</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(isset($interview->work))
                @foreach($interview->work as $key => $asset)
                    <tr>
                        <td>{{ (int)$key + 1 }}</td>
                        <td>{{ $asset->organization_name ?? 'N/A' }}</td>
                        <td>{{ $asset->starting_salary ?? 'N/A' }}</td>
                        <td>{{ $asset->last_salary ?? 'N/A' }}</td>
                      <td>{{ $asset->duration_from ? \Carbon\Carbon::parse($asset->duration_from)->format('d M Y') : 'N/A' }}</td>
<td>{{ $asset->duration_to ? \Carbon\Carbon::parse($asset->duration_to)->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $asset->reason_leave ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
                    <!-- end -->
                    <div class="mt-5">
    <h3 class="text-center mb-3">References from Previous Company</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Company Name</th>
                <th>Reporting Person</th>
                <th>Designation</th>
                <th>Mobile No</th>
                <th>Reportees</th>
            </tr>

        </thead>
        <tbody>
            @if(isset($interview->reference))
                @foreach($interview->reference as $key => $asset)
                    <tr>
                        <td>{{ (int)$key + 1 }}</td>
                        <td>{{ $asset->company_name ?? 'N/A' }}</td>
                        <td>{{ $asset->reporting_person	 ?? 'N/A' }}</td>
                        <td>{{ $asset->designation ?? 'N/A' }}</td>
                        <td>{{ $asset->mobile_no ?? 'N/A' }}</td>
                        <td>{{ $asset->reportees ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
                    <!-- end -->
<!-- level -->
              @if(isset($interviewLevel))
                             <div class="mt-5 mb-5">
    <h3 class="text-center mb-3">Interview Final Status</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Level</th>
                <th>Place</th>
                <th>Remarks</th>
                <th>status</th>

            </tr>

        </thead>
        <tbody>
            @if(isset($interviewLevel))
                @foreach($interviewLevel as $key => $item)
                    <tr>
                        <td>{{ (int)$key + 1 }}</td>
                        <td>{{ $item->level ?? 'N/A' }}</td>
                        <td>{{ $item->place	 ?? 'N/A' }}</td>
                        <td>{{ $item->description ?? 'N/A' }}</td>
                        <td>{{ $item->status ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endif

                </div>
            </div>
        </div>
    </div>
</div>
            </div>

        </div>
    </div>
</div>

