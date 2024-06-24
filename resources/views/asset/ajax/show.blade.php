

<div id="leave-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-md-10 col-10 text-center">
                            <h3 class="heading-h1">Asset Details</h3>
                        </div>
                        <div class="col-md-2 col-2 text-right">
                            <div class="dropdown">

                                    <button
                                        class="btn btn-lg f-14 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                 
                            </div>
                        </div>
                    </div>
                </div>
<div>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                   
                
                     <!-- Left side with details in p tags -->
      
                    <div class="col-lg-12">
                    <p><strong>Name:</strong> {{ ucwords($user->name)}}</p>
                    <p><strong>Address:</strong> {{  ucwords($user->employeeDetail->address) }}</p>
                    @if ($user->employeeDetail->designation)
                        <p><strong>Designation:</strong> {{ $user->employeeDetail->designation->name }}</p>
                    @else
                        <p><strong>Designation:</strong> Not specified</p>
                    @endif                    <p><strong>Date of Joining:</strong> {{ $user->employeeDetail->joining_date->timezone(company()->timezone)->translatedFormat(company()->date_format) }}</p>
                    <p><strong>Contact Number:</strong> {{ $user->mobile }}</p>
                    <p><strong>Date:</strong> {{ $asset[0]->created_at->timezone(company()->timezone)->translatedFormat(company()->date_format) }}</p>
                    <p><strong>Date:</strong> {{ucwords($asset[0]->status)}}</p>
                    <!-- Right side with the table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Particular</th>
                                <th>Model</th>
                                <th>Issued By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asset as $key => $asset)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $asset->particular }}</td>
                                    <td>{{ $asset->model }}</td>
                                    <td>{{ ucwords(user()->name) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
            
        </div>
    </div>
</div>

