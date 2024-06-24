<div class="modal-header">
    <h5 class="modal-title">Add Assign Branch</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createForm" method="POST" class="ajax-form">
            <div class="row">
            
                <div class="col-lg-6 col-md-3">
                                <x-forms.select fieldId="branch" fieldName="branch" 
                                    :fieldLabel="__('modules.assignBranch.branch')"  fieldRequired="true">
                                    <option value="">--</option>
                                    @foreach ($branch as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <span id="error_branch" class="text-danger"></span>

                </div>
                <div class="col-lg-6 col-md-3">
                                <x-forms.select fieldId="assign" fieldName="assign[]" multiple
                                    :fieldLabel="__('modules.assignBranch.assign')" fieldRequired="true">
                                    <option value="">--</option>
                                    @foreach ($user as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <span id="error_assign" class="text-danger"></span>
                 </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-custom-field_assign" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>

    $(".select-picker").selectpicker();

    var $insertBefore = $('#insertBefore');
    var $i = 1;

    // Add More Inputs
    $('#plusButton').click(function () {
        $i = $i + 1;
        var indexs = $i + 1;
        $('<div id="addMoreBox' + indexs + '" class="row my-3"> <div class="col-md-10">  <label class="control-label">@lang('app.value')</label> <input class="form-control height-35 f-14" name="value[]" type="text" value="" placeholder=""/>  </div> <div class="col-md-1"> <div class="task_view mt-4"> <a href="javascript:;" class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" onclick="removeBox(' + indexs + ')"> <i class="fa fa-trash icons mr-2"></i> @lang('app.delete')</a> </div> </div></div>').insertBefore($insertBefore);
    });

    // Remove fields
    function removeBox(index) {
        $('#addMoreBox' + index).remove();
    }

    $('#type').on('change', function () {
        (this.value === 'select' || this.value === 'radio' || this.value === 'checkbox') ? $('.mt-repeater').removeClass('d-none') : $('.mt-repeater').addClass('d-none');
    });

    function convertToSlug(Text) {
        return Text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
    }

    $('#label').keyup(function () {
        $('#name').val(convertToSlug($(this).val()));
    });

    $('#save-custom-field_assign').click(function () {
       var branchValue = $("#branch").val();
       var userValue = $("#assign").val();
       $('#error_branch').text('');
       $('#error_assign').text('');
       if (branchValue == "") {
                $('#error_branch').text('Please select a branch');
                return false;
       }
        if(userValue=="")
        {
             $('#error_assign').text('Please select a User');
                return false;
        }
    
        
        $.easyAjax({
            url: "{{route('assign-branch.store')}}",
            container: '#createForm',
            type: "POST",
            data: $('#createForm').serialize(),
            file: true,
            blockUI: true,
            buttonSelector: "#save-custom-field",
            success: function (response) {
                if (response.status === 'success') {
                     window.location.reload();
                }
            }
        })
        return false;
    })

</script>

