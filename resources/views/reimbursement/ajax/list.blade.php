<?php
use App\Helper\Files;
use App\Models\LeaveFile;
?>
@extends('layouts.app')

@push('styles')
    <style>
        .attendance-total {
            width: 10%;
        }

        .table .thead-light th,
        .table tr td,
        .table h5 {
            font-size: 12px;
        }
        .mw-250{
            min-width: 125px;
        }
        .small-image-design {
    max-width: 100px; /* Set the maximum width of the small image */
    max-height: 100px; /* Set the maximum height of the small image */
    cursor: pointer; /* Add a pointer cursor to indicate it's clickable */
}

/* Zoomed-in image container */
.image-zoom-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9); /* Semi-transparent black background */
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/css/daterangepicker.css') }}">

@endpush

@section('filter-section')
<div id="attendance-detail-section">
                     <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                         <div class="row">
                              <div class="col-md-10 col-10">
                          
                        </div>
                       
                    </div>
               
     <div class="row">
        @foreach($reimbursement as $reimbursement)
        <div class="col-sm-6">
             <div class="card bg-white border-0 b-shadow-4">
               
                <div class="card-body">
            

                       <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                        <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                            Employee Name</p>
                           
                        <p class="mb-0 text-dark-grey f-14">
                            {{ucwords($reimbursement->name)}}
                        </p>
                       
                    </div>
             
<x-cards.data-row :label="__('Date of Expense')" :value="$reimbursement->date_of_expense ? \Carbon\Carbon::parse($reimbursement->date_of_expense)->format('d-m-Y') : ''" html="true" />


                    <x-cards.data-row :label="__('Amount')" :value="$reimbursement->amount"
                        html="true" />
                        <x-cards.data-row :label="__('Payment Type')" :value="$reimbursement->payment_type"
                        html="true" />
                        <x-cards.data-row :label="__('Expense Type')" :value="$reimbursement->expense_type"
                        html="true" />
                          <x-cards.data-row :label="__('Purpose')" :value="$reimbursement->purpose?$reimbursement->purpose:'N/A'"
                            html="true" />
                          @php
                            $color = ($reimbursement->status == "Approved") ? "green" : "red";
                            $already_paid=$reimbursement->already_paid=="true";
                            $colorModified=$reimbursement->already_paid=="true"?"green":$color;

                          @endphp
                        <x-cards.data-row :label="__('Status')" :value="$already_paid?'Already Paid':$reimbursement->status " html="true" :color="$colorModified" />
                        <div class="data-row ">
                       
        @if ($reimbursement->file)
        <a href="{{asset_url_local_s3(LeaveFile::FILE_PATH .'/'.$reimbursement->file, true, 'image') }}" target="_blank">
            <img src="{{asset_url_local_s3(LeaveFile::FILE_PATH .'/'.$reimbursement->file, true, 'image') }}" alt="Attachment Image" style="height:250px;" >
        </a>
        @else
            <p>No Image</p>
        @endif
</div>
<div class="d-flex justify-content-end ">
    @if($reimbursement->status !=="Approved" && $reimbursement->status !=="Rejected" && $reimbursement->already_paid == "false")<button class="btn btn-primary mr-2 approved" data-reimbursement-action="approved" data-reimbursement-id="{{$reimbursement->uniqueId}}">Approved</button>@endif @if($reimbursement->status !=="Rejected" && $reimbursement->status !=="Approved" && $reimbursement->already_paid == "false")<button class="btn btn-primary mr-2 rejected"   data-reimbursement-action="rejected" data-reimbursement-id="{{$reimbursement->uniqueId}}">Rejected</button>@endif @if($reimbursement->already_paid == "false" && $reimbursement->status !=="Approved" && $reimbursement->status !=="Rejected")<button class="btn btn-primary already-paid" data-reimbursement-action="already paid"   data-reimbursement-id="{{$reimbursement->uniqueId}}">Already Paid</button>@endif
</div>
                </div>
               
             </div> 
        </div>
       @endforeach
     </div>

      
</div>

<div class="image-zoom-container"></div>

            
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const smallImage = document.querySelector('.small-image-design');
        const zoomContainer = document.querySelector('.image-zoom-container');

        smallImage.addEventListener('click', function () {
            // Show the zoomed-in image container
            zoomContainer.style.display = 'flex';

            // Create a new image element for the zoomed-in image
            const zoomedInImage = new Image();
            zoomedInImage.src = this.src;

            // Add the zoomed-in image to the container
            zoomContainer.innerHTML = '';
            zoomContainer.appendChild(zoomedInImage);

            // Close the zoomed-in image when clicked
            zoomContainer.addEventListener('click', function () {
                zoomContainer.style.display = 'none';
            });
        });
    });
</script>

<script>
    $('body').on('click', '.approved,.rejected, .already-paid', function () {
      var reimbursementId = $(this).data('reimbursement-id');
      var action=$(this).data('reimbursement-action');
      console.log(action,reimbursementId);
      $.ajax({
          url: '{{ route('reimbursement.status','') }}/'+reimbursementId,
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
          action: action
      },
          success: function (response) {
              toastr.success('Status Updated Successfully!');
              location.reload();
  
          },
          error: function (xhr, status, error) {
              console.error(error);
          }
      });
  });
    
</script>
@endpush


