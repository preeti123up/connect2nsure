<div class="modal-header">
    <h5 class="modal-title">Add Branch</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createForm" method="POST" class="ajax-form">
            <div class="row">
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Branch Name')" fieldName="branch_name" fieldId="branch_name"  fieldRequired="true"  />
                    <span id="sp_branch_name" data-name="branch_name" data-msg="branch name"></span>
                </div>
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Branch Code')" fieldName="branch_code" fieldId="branch_code"  fieldRequired="true"  />
                    <span id="sp_branch_code" data-name="branch_code" data-msg="branch code"></span>
                </div>
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Latitude')" fieldName="latitude" fieldId="latitude"  fieldRequired="true"  />
                    <span id="sp_latitude" data-name="latitude" data-msg="latitude"></span>
                </div>
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Longitude')" fieldName="longitude" fieldId="longitude"  fieldRequired="true"  />
                    <span id="sp_longitude" data-name="longitude" data-msg="longitude"></span>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-custom-field" icon="check">@lang('app.save')</x-forms.button-primary>
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

    $('#save-custom-field').click(function () {

        if(!onlyName($("#branch_name").val(),$("#sp_branch_name").attr('data-msg'),$("#sp_branch_name").attr('data-name'),['branch_code','latitude','longitude']))
        {
           return false;
        }
        if(!onlyName($("#branch_code").val(),$("#sp_branch_code").attr('data-msg'),$("#sp_branch_code").attr('data-name'),['branch_name','latitude','longitude']))
        {
           return false;
        }
        if(!number($("#latitude").val(),$("#sp_latitude").attr('data-msg'),$("#sp_latitude").attr('data-name'),['branch_name','branch_code','longitude']))
        {
           return false;
        }
        if(!number($("#longitude").val(),$("#sp_longitude").attr('data-msg'),$("#sp_longitude").attr('data-name'),['latitude','branch_code','branch_name']))
        {
           return false;
        }
        $.easyAjax({
            url: "{{route('branch.store')}}",
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

