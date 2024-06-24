<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="icon" href="{{ asset('/user-uploads/app-logo/' . $logo->logo) }}" type="image/x-icon">

    <style>
        /* Custom style for the error class */
        .error {
            border: 1px solid red !important;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-size:13px;
        }



        button {
            margin-top: 10px;
        }
        input {
            width: 100%;
            box-sizing: border-box;
        }
        .radio{
            margin-left:33px;
            height:15px;
            width:15px;
        }
        .radiobutton{
            outline:1px solid red;
        }
        body {
	font-family: Verdana, Geneva, sans-serif;
	background: #f2f2f2;
}
  .form_wrapper {
	background: #fff;
    border-radius:10px;
}
fieldset{
    background: #fbfbfb;
    padding: 20px 10px 10px 15px;
    border: 1px solid rgb(231 231 231);
    width: 100%;
    float: left;
    position: relative;
    margin: 0px 0 30px 0;
    border-radius: 10px;
}
legend {
    background: #ffffff;
    position: absolute;
    width: auto;
    top: -21px;
    padding: 6px 15px;
    border-radius: 20px;
    border: 1px solid #dfdfdf;
    color: #413f3f;
    font-size: 15px;
    font-weight: 600;

}
    </style>
</head>
<body>
<section class="contact-section sp-100-70">
    <div class="container">
     @if(!is_null($frontDetail->contact_html))

        <div class="col-md-10 mx-auto">
            {{!! $frontDetail->contact_html !!}}

        </div>
    @endif
    @php
    use Illuminate\Support\Facades\Request;
    use App\Models\Company;
    $companyId = Request::segment(2);
    $company = Company::find((int)$companyId);
@endphp
  
    <div class="row d-flex justify-content-center">
        <div class="col-md-10 form_wrapper p-4">
 <div class="row">
    <div class="col-md-6 d-flex flex-column align-items-start justify-content-center">
        <p style="font-size: 20px; font-weight: 600;">Interview Form</p>
    </div>
    <div class="col-md-6 d-flex flex-column align-items-end justify-content-center">
        <img width="80" src="{{ asset('/user-uploads/app-logo/' . $company->logo) }}" alt="">
        <p style="font-size: 12px; font-weight: 600;">{{ $company->company_name }}</p>
    </div>
</div>


            <form id="apply">
            <div class="row mb-3 after">
                 <div id="alert" class="col-sm-12"></div>
            </div>
            <div class="row" id="contactUsBox">
        <fieldset data-step="1">
                 <legend>CANDIDATE DETAIL</legend>
                    <div class="row p-2">
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="applied_position" class="form-control" placeholder="Applied For" id="applied_position">
                            
                            <input type="number" name="company_id" value="{{ $company->id }}" hidden>
                            
                        </div>
                      
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="technology" class="form-control" placeholder="Technology" id="technology">
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="name" onInput="validateName(this)" class="form-control" placeholder="Candidate Name" id="name">
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="fName" class="form-control" onInput="validateName(this)" placeholder="Father Name" id="fName">
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="dob" id="dob"   onchange="validateDate(this)" class="form-control datepicker_birth" placeholder="Date Of Birth">

                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="phone_number" onInput="validateMobile(this)" class="form-control" placeholder="Mobile Number" id="phone_number" onInput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 10) { this.value = this.value.slice(0, 10); this.value = this.value.replace(/[^0-9]/g, ''); }">
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="email" class="form-control" onInput="validateEmail(this)" placeholder="Email ID" id="email">
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <input type="text" name="pan" class="form-control" placeholder="PAN No"  onInput="validatePan(this)" id="pan">
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12">
                       <select name="blood_group" class="form-control" id="bGroup" required>
        <option value="">Select Blood Group</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
    </select>
                        </div>
                        <div class="form-group mb-4 col-lg-6 col-12 d-flex align-items-center">
                            <span style="font-size: 14px">Martial Status</span>

                            <input type="radio" name="martial_s" value="Married" class="radio" id="married" > &nbsp;&nbsp;&nbsp;<span style="font-size: 14px ">Married</span>


                            <input type="radio" name="martial_s" value="Unmarried" class="radio" id="unmarried" checked>&nbsp;&nbsp;&nbsp;<span style="font-size: 14px">Unmarried</span>

                        </div>


                        <div class="form-group mb-4 col-lg-6 col-12 d-flex align-items-center" id="g">
                            <span style="font-size: 14px">Gender</span>
                            <input type="radio" name="gender" class="radio" value="M" id="male" checked>&nbsp;&nbsp;&nbsp;<span style="font-size: 14px">Male</span>

                            <input type="radio" name="gender" class="radio" value="F" id="female">&nbsp;&nbsp;&nbsp;<span style="font-size: 14px">Female</span>


                        </div>

                        <div class="form-group mb-4 col-lg-6 col-12 d-flex align-items-center" id="r">
                            <span style="font-size: 14px">Passport</span>

                            <input type="radio" name="passport"  class="radio " value="Yes" class="ml-5" id="male">&nbsp;&nbsp;&nbsp;<span style="font-size: 14px">Yes</span>

                            <input type="radio" name="passport" class="radio " value="No" class="ml-2" id="female">&nbsp;&nbsp;&nbsp;<span style="font-size: 14px">No</span>


                        </div>

                        <div class="form-group mb-4 col-lg-12 col-12">
                            <input type="text" name="passport_number"  onInput="validatePassport(this)" class="form-control" placeholder="Passport No" id="passportN">
                        </div>


                        <div class="form-group mb-4 col-lg-6 col-12">
                            <textarea name="residential" placeholder="Residential Address" id="residential" cols="10" rows="5" class="form-control"></textarea>
                           <div class="row p-0">
                            <div class="col-md-2 p-0 mt-2">
                                <input type="checkbox" id="sameAddressCheckbox">
                            </div>
                            <div class="col-md-10 p-0 mt-2">
                                <label for="sameAddressCheckbox">Same as Residential Address</label>
                            </div>
                           </div>
            
                        </div>
                     
                        
                        <div class="form-group mb-4 col-lg-6 col-12">
                            <textarea name="address" placeholder="Permanent Address" id="address" cols="10" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-4 col-lg-12 col-12">
                            <input type="text" name="referredBy" class="form-control" onInput="validateName(this)" placeholder="Referred By (If Applicable)" id="referredBy">
                        </div>
                     </div>
            </fieldset>
                            <!-- Initial set of fields -->
                            <div class='form-group mb-4  col-lg-12 col-12 p-0'>
                            <fieldset data-step="2">
                                <legend>Family Details</legend>

                            <div class="table-responsive  mt-3">
                                <table id="dependentTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No</th>
                                            <th>Profession</th>
                                            <th>Dependent on you? Y/N</th>
                                            <th>Add Row</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text"  class="form-control" name="nameQ[]" onInput="validateName(this)" required></td>
                                            <td><input type="text" class="form-control" name="relation[]"  onInput="validateName(this)" required></td>
                                            <td><input type="text" class="form-control" name="mobile[]" onInput="validateMobile(this)" required></td>
                                            <td><input type="text"  class="form-control" name="profession[]" required></td>
                                            <td><input type="text" class="form-control" name="dependent[]" onInput="validateName(this)" onInput="this.value=this.value.toUpperCase()"></td>
                                            <td><button type="button"  class="form-control mb-2 btn-info" onclick="addRow()" class="ml-3 mb-3">+</button>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </fieldset>
                        </div>

                        <!-- Academic & professional Qualification -->
                        <div class='form-group mb-4 col-lg-12 col-12 p-0'>
                            <fieldset data-step="3">
                                <legend>Academic & Professional Qalification</legend>

                            <div class="table-responsive  mt-3">
                            <table id="dependentTable2">
                                    <thead>
                                        <tr>
                                            <th>Degree/Diploma/Others</th>
                                            <th>Board/University</th>
                                            <th>Year of Passing</th>
                                            <th>Percentage</th>
                                            <th>Add Row</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="qualification[]"  required></td>
                                            <td><input type="text" class="form-control" name="board[]"  required></td>
                                            <td><input type="text" class="form-control" name="passingY[]" required></td>
                                            <td><input type="text"  class="form-control" name="percentage[]" required></td>
                                            <td>
                                                <button type="button"  class="form-control mb-2 btn-info" onclick="addRow2()" class="ml-3 mb-3">+</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </fieldset>
                        </div>
                        <!-- end -->
                        <!-- Work Experience(Till Date) -->
                        <div class='form-group mb-4 col-lg-12 col-12 p-0'>
                            <fieldset data-step="4">
                                <legend>WORK EXPERIENCE (TILL DATE)</legend>

                            <div class="table-responsive mt-3">
                            <table id="dependentTable3">
                                    <thead>
                                        <tr>
                                            <th>Name of the Organization</th>
                                            <th colspan="2">Salary Per month</th>
                                            <th colspan='2'>Tenure/Duration</th>
                                            <th>Reason to Leave</th>
                                            <th>Add Row</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>Salary(At the time of Joining)</th>
                                            <th>Last Drawn Salary</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th></th>
                                            <th></th>
                                        </tr>


                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text"  class="form-control" name="organization[]" onInput="validateName(this)" required></td>
                                            <td><input type="text" class="form-control" name="salary[]" required></td>
                                            <td><input type="text" class="form-control" name="lastDrawn[]" required></td>
                                   <td style="width: 150px;"><input type="text" class="form-control datepicker" name="From[]" onchange="openToCalendar(this)" required></td>
<td style="width: 150px;"><input type="text" class="form-control datepicker" name="to[]" id="toDateInput"  onchange="validateDate(this)" required></td>
                                            <td><input type="text"  class="form-control" name="rLeave[]" required></td>
                                            <td>
                                                <button type="button"  class="form-control mb-2 btn-info" onclick="addRow3()" class="ml-3 mb-3">+</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </fieldset>
                        </div>
                  <!-- end -->
<fieldset data-step="5">
                           <legend>References From Previous Company</legend>
                           <h6 class="text-left my-3 mt-3">References -1</h6>
                           <div class="row p-2">
<div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="company[]" class="form-control" placeholder="Company Name"  onInput="validateName(this)" id="company1">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="rPerson[]" class="form-control"  onInput="validateName(this)" placeholder="Reporting Person" id="rPerson1">
                </div>

               
                <div class="form-group mb-4 col-lg-6 col-12"> 
                    <input type="text" name="designation[]" class="form-control" placeholder="Designation"  onInput="validateName(this)" id="designation1">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="mobileR[]" class="form-control"  onInput="validateMobile(this)" placeholder="Mobile No" id="mobile1">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="reportees[]" class="form-control" placeholder="Hom Many Reportees" id="reportees1">
                </div>

 <div class='col-12'>
<h6 class="text-left my-3">References -2</h6>
</div>


<div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="company[]" class="form-control" placeholder="Company Name" id="company2">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="rPerson[]" class="form-control" placeholder="Reporting Person" id="rPerson2">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="designation[]" class="form-control" placeholder="Designation" id="designation2">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="mobileR[]" class="form-control" placeholder="Mobile No" id="mobile2">
                </div>
                <div class="form-group mb-4 col-lg-6 col-12">
                    <input type="text" name="reportees[]" class="form-control" placeholder="Hom Many Reportees" id="reportees2">
                </div>


<!-- end -->
              <div class="form-group mb-4 col-12">
    <label for="cv" class="custom-file-upload">
        <i class="fas fa-cloud-upload-alt"></i> Upload CV
    </label>
    <input type="file" name="cv" class="form-control" id="cv">
</div>
<div class="form-group mb-4 col-12">
    <p style="color:red;">Note: Please double-check the form before submitting.</p>
</div>
</div>
</fieldset>
<div class="form-group text-center justify-content-center mb-0 col-12">
    <button type="button" class="btn btn-primary mt-1" id="prevBtn">Previous</button>
    <button type="button"  class="btn btn-primary mt-1" id="nextBtn">Next</button>
</div>
<div class="form-group text-center mb-0 col-12">
                            <button type="button" class="btn btn-sm btn-warning mt-2" id="apply-submit">
                               Submit
                            </button>
                        </div>
            </div>
    </form>
        </div>
    </div>
</div>

</div>


</section>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    function checkSelected(id) {
        console.log(id)
        var radios = document.getElementsByName(id);

        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                console.log('Selected value:', radios[i].value);
                return radios[i].value;
            }
        }

        // If no radio button is selected
        return null;
    }

    // Call the function and store the result in a variable

</script>
<script>
    $(document).ready(function() {
        $('#sameAddressCheckbox').change(function() {
            if ($(this).is(':checked')) {
                $('#address').val($('#residential').val());
            } else {
                $('#address').val('');
            }
        });

        $('#residential').on('input', function() {
            if ($('#sameAddressCheckbox').is(':checked')) {
                $('#address').val($(this).val());
            }
        });
    });
</script>
<script>
    $('#apply-submit').click(function(){
        var formvalidated = validateForm();
  if(formvalidated == false){
    return;
    }
        var formData = new FormData($('#apply')[0]);
        var name = $('#name').val();
    var email = $('#email').val();
    var phoneNumber = $('#phone_number').val();
    var dob = $('#dob').val();
    var appliedPosition = $('#applied_position').val();
    var fathername = $('#fName').val();
    var technology = $('#technology').val();
    var cv = $('#cv')[0].files[0];
    var m = checkSelected('martial_s');
    var g = checkSelected('gender');

    $('<span style="color: red;">Please select Martial Status</span>').remove();

    $('#apply input').removeClass('error');
    $('#apply input').removeClass('radiobutton');
    // Perform your specific validation logic here
    if (!name ||!technology||!dob||!fathername|| !email || !phoneNumber || !address || !appliedPosition || !cv||!m||!g) {
   
    if (!appliedPosition) {
        $('input[name="applied_position"]').addClass('error');
     
    }
    if (!technology) {
        $('input[name="technology"]').addClass('error');
     
    }
    if (!name) {
        $('input[name="name"]').addClass('error');
       
    }
    if (!fathername) {
        $('input[name="fName"]').addClass('error');
    
    }
    if (!dob) {
        $('input[name="dob"]').addClass('error');
     
    }
    if (!phoneNumber) {
        $('input[name="phone_number"]').addClass('error');
       
    }
  
    if (!email) {
        $('input[name="email"]').addClass('error');
      
    }

    if (!m) {
        $('input[name="martial_s"]').addClass('radiobutton');
  
     }
    if (!g) {
        $('input[name="gender"]').addClass('radiobutton');
    
    }
    if (!cv) {
        $('input[name="cv"]').addClass('error');
   
    }

    return;
}

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $(this).prop('disabled', true);

        $.ajax({
            url: "{{ route('front.applySubmit')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'success') {
                    $('#contactUsBox').remove();
                    $(`<span style="color: #1FB792; display: block; text-align: center; margin-bottom:60px; font-weight: bold;">${response.message}</span>`).insertAfter('.after');
                }
            },
            error: function (error) {
        console.log("AJAX Error:", error);
    }
        });
    });

</script>
<script>
    function addRow() {
        var table = document.getElementById("dependentTable").getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        var columns = 6; // Number of columns in the table
        for (var i = 0; i < columns - 1; i++) {
            var cell = newRow.insertCell(i);
            var input = document.createElement("input");
            input.type = "text";
            input.name = ["nameQ[]", "relation[]", "mobile[]", "profession[]","dependent[]"][i];
            input.required = true;
            input.classList.add('form-control');
             // Add the onInput event for the mobile field
           if (input.name === "mobile[]") {
            input.setAttribute("onInput", "validateMobile(this)");
           }
            cell.appendChild(input);
        }

        var actionCell = newRow.insertCell(columns - 1);
        var removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.innerHTML = "-";
        removeButton.classList.add('form-control','mb-2','btn-danger');
        removeButton.onclick = function () { removeRow(this); };
        actionCell.appendChild(removeButton);
    }

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }


    function addRow2() {
        var table = document.getElementById("dependentTable2").getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        var columns = 5; // Number of columns in the table
        for (var i = 0; i < columns - 1; i++) {
            var cell = newRow.insertCell(i);
            var input = document.createElement("input");
            input.type = "text";
            input.name =  ["qualification[]", "board[]", "passingY[]", "percentage[]"][i];
            input.classList.add('form-control');
            input.required = true;
            cell.appendChild(input);
        }

        var actionCell = newRow.insertCell(columns - 1);
        var removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.innerHTML = "-";
        removeButton.classList.add('form-control','mb-2','btn-danger');
        removeButton.onclick = function () { removeRow(this); };
        actionCell.appendChild(removeButton);
    }

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }


    function addRow3() {
        var table = document.getElementById("dependentTable3").getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        var columns = 7; // Number of columns in the table
        for (var i = 0; i < columns - 1; i++) {
    var cell = newRow.insertCell(i);
    var input = document.createElement("input");
    input.type = "text";
    input.name = ["organization[]", "salary[]", "lastDrawn[]", "From[]", "to[]", "rLeave[]"][i];
    input.classList.add('form-control');
    if (input.name === "From[]" || input.name === "to[]") {
        input.classList.add('datepicker'); 
    }
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy'
      });
    input.required = true;
    cell.appendChild(input);
}

        var actionCell = newRow.insertCell(columns - 1);
        var removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.innerHTML = "-";
        removeButton.classList.add('form-control','mb-2','btn-danger');
        removeButton.onclick = function () { removeRow(this); };
        actionCell.appendChild(removeButton);
    }

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    $('input[name="passport"]').change(function() {
                if ($(this).val() === 'Yes') {
                    $('#passportN').show();
                } else {
                    $('#passportN').hide();
                }
            });
</script>
<script>
    let currentStep = 1;

    function showStep(step) {
      
        $('fieldset').hide();
      
        $(`fieldset[data-step="${step}"]`).show();
  
        if (step === 1) {
            $('#prevBtn').hide();
        } else {
            $('#prevBtn').show();
        }
        if (step === $('fieldset').length) {
            $('#nextBtn').hide();
            $('#apply-submit').show();
            
        } else {
            $('#nextBtn').show();
            $('#apply-submit').hide();
        }
    }

    function nextPrev(step) {
        if (step === 1) {
            currentStep += 1;
        } else if (step === -1) {
            currentStep -= 1;
        }
        showStep(currentStep);
    }

    $(document).ready(function() {
        showStep(currentStep);
        $('#nextBtn').click(function() {
            nextPrev(1);
        });
        $('#prevBtn').click(function() {
            nextPrev(-1);
        });
    });
</script>
<script>
  function validateMobile(inputElement) {
    inputElement.value = inputElement.value.replace(/[^0-9]/g, '');
    if (inputElement.value !== '') {
        if (inputElement.value.length > 10) {
            inputElement.value = inputElement.value.slice(0, 10);
        }

        const indianMobileNumberRegex = /^[6-9]{1}[0-9]{9}$/;
        const isValid = indianMobileNumberRegex.test(inputElement.value);

        if (isValid) {
            inputElement.classList.remove('error');
            $('#apply-submit').prop('disabled', false); // Enable #apply-submit if mobile number is valid
          
        } else {
            inputElement.classList.add('error');
            $('#apply-submit').prop('disabled', true); // Disable #apply-submit if mobile number is invalid
        }
    } else {
        inputElement.classList.remove('error');
        $('#apply-submit').prop('disabled', false); // Disable #apply-submit if mobile number is empty
      
    }
}

function validateEmail(inputElement) {
    const email = inputElement.value.trim();
    if (email !== '') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/; // Requires at least 2 letters after the dot
        const isValid = emailRegex.test(email);
        
        if (isValid) {
            inputElement.classList.remove('error');
            $('#apply-submit').prop('disabled', false); // Enable #apply-submit if name is valid
          
        } else {
            inputElement.classList.add('error');
            $('#apply-submit').prop('disabled', true); // Disable #apply-submit if there's an error in the name
        }
    } else {
        inputElement.classList.remove('error');
        $('#apply-submit').prop('disabled', false); // Disable #apply-submit if name is empty
      
    }
}
function validateName(inputElement) {
    const name = inputElement.value.trim();
    if (name !== '') {
        // Assuming name should contain only letters and spaces
        const nameRegex = /^[a-zA-Z\s]+$/;
        const isValid = nameRegex.test(name);
        
        if (isValid) {
            inputElement.classList.remove('error');
            $('#apply-submit').prop('disabled', false); // Enable #apply-submit if name is valid
          
        } else {
            inputElement.classList.add('error');
            $('#apply-submit').prop('disabled', true); // Disable #apply-submit if there's an error in the name
        }
    } else {
        inputElement.classList.remove('error');
        $('#apply-submit').prop('disabled', false); // Disable #apply-submit if name is empty
      
    }
}


function validatePassport(inputElement) {
    const passportNumber = inputElement.value.trim();
    if (passportNumber !== '') {
      const passportRegex = /^[A-Z]{1}[0-9]{7}$/;

        const isValid = passportRegex.test(passportNumber);

        if (isValid) {
            inputElement.classList.remove('error');
            $('#apply-submit').prop('disabled', false); // Enable #apply-submit if passport number is valid
        
        } else {
            inputElement.classList.add('error');
            $('#apply-submit').prop('disabled', true); // Disable #apply-submit if there's an error in the passport number
        }
    } else {
        inputElement.classList.remove('error');
        $('#apply-submit').prop('disabled', false); // Disable #apply-submit if passport number is empty
       
    }
}




function validatePan(inputElement) {
    const panNumber = inputElement.value.trim().toUpperCase();
    if (panNumber !== '') {
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        const isValid = panRegex.test(panNumber);

        if (isValid) {
            inputElement.classList.remove('error');
               $('#apply-submit').prop('disabled', false); // Enable #apply-submit if no error
             
        } else {
            inputElement.classList.add('error');
            $('#apply-submit').prop('disabled', true); // Disable #apply-submit if there's an error
           
        }
    } else {
        inputElement.classList.remove('error');
       validateForm();
         $('#apply-submit').prop('disabled', false); // Enable #apply-submit if no error
        
    }
   
}





</script>




<script>
  
    $('.datepicker').datepicker({
    dateFormat: 'dd-mm-yy',
});
  $('.datepicker_birth').datepicker({
    dateFormat: 'dd-mm-yy',
    defaultDate: '-18y',
    maxDate: '-18y',
    onSelect: function(dateText, inst) {
        validateDateOfBirth(this);
    }
}).on('change', function() {
    validateDateOfBirth(this);
});

function validateDateOfBirth(inputElement) {
    // Get the entered value
    var enteredDate = inputElement.value;
    
    // Parse the entered date using a specific format
    var parts = enteredDate.split('-');
    var enteredYear = parseInt(parts[2], 10);
    var enteredMonth = parseInt(parts[1], 10) - 1; // Months are zero-based
    var enteredDay = parseInt(parts[0], 10);

    // Create a Date object with the parsed values
    var selectedDate = new Date(enteredYear, enteredMonth, enteredDay);

    // Calculate the date 18 years ago
    var eighteenYearsAgo = new Date();
    eighteenYearsAgo.setFullYear(eighteenYearsAgo.getFullYear() - 18);

    // If the entered date is after 18 years ago, clear the input field
    if (selectedDate > eighteenYearsAgo) {
        inputElement.value = ''; // Clear the input field
    }
}


  </script>
 <script>
 function openToCalendar(selectedFromDate) {
    var dateRegex = /^\d{2}-\d{2}-\d{4}$/;
    var isValid = dateRegex.test(selectedFromDate.value);
    var toDateInput = document.querySelector("input[name='to[]']");

    if (isValid) {
        toDateInput.value = '';
        $(toDateInput).datepicker("option", "minDate", selectedFromDate.value);
    } else {
        selectedFromDate.value = '';
        toDateInput.value ='';
    }
}

 </script>

 <script>
    function validateDate(inputDate) {
        if(inputDate.value !== ''){
            var dateRegex = /^\d{2}-\d{2}-\d{4}$/;
    var isValid = dateRegex.test(inputDate.value);
    if (isValid) {
        inputDate.classList.remove('error');
    } else {
        inputDate.classList.add('error');
        inputDate.value = '';
    }
        }
   
}

function validateForm() {
    var mobileElement = document.getElementById('phone_number'); 
    var emailElement = document.getElementById('email'); 
    var nameElement = document.getElementById('name'); 
    var passportElement = document.getElementById('passportN'); 
    var panElement = document.getElementById('pan'); 
    // Check if any of the elements have the error class
if ($('#phone_number').hasClass('error') || 
    $('#email').hasClass('error') || 
    $('#name').hasClass('error') || 
    $('#passportN').hasClass('error') || 
    $('#pan').hasClass('error')) {
  
    $('#apply-submit').prop('disabled', true);
    return false;
} else {
  return true;
    $('#apply-submit').prop('disabled', false);
}

  
}
</script>
</body>
</html>
