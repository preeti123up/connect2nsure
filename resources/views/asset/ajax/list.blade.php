<style>
    .card-img {
        border-radius: 50%;
        height: 42px;
        width: 43px;
        margin: 0 12px 0 0;
    }

    .card-img img {
        height: auto;
    }
</style>

<div id="leave-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="heading-h1 text-center">Asset Details</h4>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row" style="margin:auto;">
                                    <!-- Left side with details in p tags -->
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped mt-2">
                                                <tbody>
                                                    <tr>
                                                        <th>Asset Name:</th>
                                                        <td>{{ $asset->asset_name ?? 'N/A' }}</td>
                                                        <th>Asset Type:</th>
                                                        <td>{{ $asset->assetDevice->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status:</th>
                                                        <td>{{ $asset->status ?? 'N/A' }}</td>
                                                        <th>Serial No.:</th>
                                                        <td>{{ $asset->serial_number ?? 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <h3 class="mt-3">History</h3>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-striped mt-2">
                                                <thead>
                                                    <tr>
                                                        <th>Lend To</th>
                                                        <th>Allocation Date</th>
                                                        <th>Estimated Date Of Return</th>
                                                        <th>Return Date</th>
                                                        <th>Return By</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($asset->lendedAsset as $lend_user)
                                                    <tr>
                                                        <td style="padding-left:0;">
                                                            <div class="card-horizontal align-items-center mt-0">
                                                                <div class="card-img">
                                                                    <img class=""
                                                                        src="{{ $lend_user->user->image_url }}"
                                                                        alt="Card image">
                                                                </div>
                                                                <div class="card-body border-0 p-0">
                                                                    <h4 class="card-title text-dark f-13 f-w-500 mb-0">
                                                                        {{ $lend_user->user->name }}</h4>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $lend_user->given_date ?
                                                            \Carbon\Carbon::createFromTimestamp(strtotime($lend_user->given_date))->format('d
                                                            M Y') : '--' }}</td>
                                                        <td>{{ $lend_user->estimated_return_date ?
                                                            \Carbon\Carbon::createFromTimestamp(strtotime($lend_user->estimated_return_date))->format('d
                                                            M Y') : '--' }}</td>
                                                        <td>{{ $lend_user->date_of_return ?
                                                            \Carbon\Carbon::createFromTimestamp(strtotime($lend_user->date_of_return))->format('d
                                                            M Y') : '--' }}</td>
                                                        <?php $return_user = \App\Models\User::where('id', $lend_user->return_by)->first();?>
                                                        <td style="padding-left:0;">
                                                            @if($return_user)
                                                            <div class="col-lg-9 pl-0">
                                                                <div class="card-horizontal align-items-center">
                                                                    <div class="card-img">
                                                                        <img class=""
                                                                            src="{{ $return_user->image_url }}"
                                                                            alt="Card image">
                                                                    </div>
                                                                    <div class="card-body border-0 p-0">
                                                                        <h4
                                                                            class="card-title text-dark f-13 f-w-500 mb-0">
                                                                            {{ $return_user->name }}</h4>
                                                                        <p
                                                                            class="f-3 font-weight-normal text-dark-grey mb-2">
                                                                            {{
                                                                            $return_user->employeeDetails->designation->name
                                                                            ?? '--' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @else
                                                            <div class="col-lg-9">
                                                                --
                                                            </div>
                                                            @endif
                                                        </td>
                                                        @if($lend_user->lend_status == "return")
                                                        <td>{{ $lend_user->return_notes }}</td>
                                                        @else
                                                        <td>{{ $lend_user->notes }}</td>
                                                        @endif
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
    </div>
</div>