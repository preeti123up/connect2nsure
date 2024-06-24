<div class="modal-header">
    <h5 class="modal-title">Edit Branch</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editForm" method="Post" class="form-horizontal">

            <div class="row">
                <div class="col-lg-6">

                    <x-forms.text :fieldLabel="__('Branch Name')" fieldName="branch_name" fieldId="branch_name"  :fieldValue="$branch->branch_name" fieldRequired="true"  />
                    <span id="sp_branch_name" data-name="branch_name" data-msg="branch name" ></span>
                </div>
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Branch Code')" fieldName="branch_code" fieldId="branch_code"  :fieldValue="$branch->branch_code" fieldRequired="true"  />
                    <span id="sp_branch_code" data-name="branch_code" data-msg="branch code"></span>
                </div>
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Latitude')" fieldName="latitude" fieldId="latitude" :fieldValue="$branch->lat" fieldRequired="true"  />
                    <span id="sp_latitude" data-name="latitude" data-msg="latitude"></span>
                </div>
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('Longitude')" fieldName="longitude" fieldId="longitude" :fieldValue="$branch->long" fieldRequired="true"  />
                    <span id="sp_longitude" data-name="longitude" data-msg="longitude"></span>
                </div>
            </div>

        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-custom-field" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>


    $('#update-custom-field').click(function () {

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
            url: "{{route('branch-master.update', base64_encode($branch->id))}}",
            container: '#editForm',
            type: "POST",
            data: $('#editForm').serialize(),
            file:true,
            blockUI: true,
            buttonSelector: "#update-custom-field",
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });

</script>

