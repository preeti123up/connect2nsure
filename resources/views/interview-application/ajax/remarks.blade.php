
<div class="mt-2">
    <div class="row">
        <!-- Interview Level 1 -->
        @if($level1 !== null)
            <div class="col-md-6">
                <div class="card bg-light mb-3">
                    <div class="card-header bg-white border-bottom-grey text-capitalize">
                        <h3 class="text-center">Interview Level 1</h3>
                        <h4 class="text-center">By {{ $level1->user->name }}</h4>
                    </div>
                    <div class="card-body">
                        <x-form id="remarks-form">
                            <div class="form-group">
                                <label for="remarks">Place</label>
                                <input type="text" name="place" value="{{ $level1->place }}" class="form-control h-1"/>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="description" id="description" cols="14" rows="5" class="form-control">{{ $level1->description }}</textarea>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <span style="font-size: 13px">Selected</span>
                                <input type="radio" name="remarks" class="radio ml-2 mr-3" value="Selected" id="male" @if($level1->status == 'Selected') checked @endif>
                                <span style="font-size: 13px">Recommended</span>
                                <input type="radio" name="remarks" class="radio ml-1 mr-3" value="Recommended" class="ml-2" id="female" @if($level1->status == 'Recommended') checked @endif>
                                <span style="font-size: 13px">On Hold</span>
                                <input type="radio" name="remarks" class="radio ml-1 mr-3" value="On Hold" class="ml-2" id="female" @if($level1->status == 'On Hold') checked @endif>
                                <span style="font-size: 13px">Rejected</span>
                                <input type="radio" name="remarks" class="radio ml-1 mr-3" value="Rejected" class="ml-2" id="female" @if($level1->status == 'Rejected') checked @endif>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        @else
            <!-- Form for Level 1 when data is not present -->
            <div class="col-md-6">
                <div class="card bg-light mb-3">
                    <div class="card-header bg-white border-bottom-grey text-capitalize">
                        <h3 class="text-center">Interview Level 1</h3>
                    </div>
                    <div class="card-body">
                        <x-form id="remarks-form">
                            <div class="form-group">
                                <label for="remarks">Place</label>
                                <input type="text" name="place" class="form-control h-1"/>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="description" id="description" cols="14" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <span style="font-size: 13px">Selected</span>
                                <input type="radio" name="remarks" class="radio ml-2 mr-3" value="Selected" id="male">
                                <span style="font-size: 13px">Recommended</span>
                                <input type="radio" name="remarks" class="radio ml-1 mr-3" value="Recommended" class="ml-2" id="female">
                                <span style="font-size: 13px">On Hold</span>
                                <input type="radio" name="remarks" class="radio ml-1 mr-3" value="On Hold" class="ml-2" id="female">
                                <span style="font-size: 13px">Rejected</span>
                                <input type="radio" name="remarks" class="radio ml-1 mr-3" value="Rejected" class="ml-2" id="female">
                            </div>
                            <div class="form-group text-center mb-0">
                                <button class="btn btn-primary btn-sm" id="save-remarks-form" value="level1">Submit</button>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Interview Level 2 -->
        @if($level2 !== null)
            <div class="col-md-6">
                <div class="card bg-light mb-3">
                    <div class="card-header bg-white border-bottom-grey text-capitalize">
                        <h3 class="text-center">Interview Level 2 (System Test)</h3>
                        <h4 class="text-center">By {{ $level2->user->name }}</h4>
                    </div>
                    <div class="card-body">
                        <x-form id="remarks-form2">
                            <div class="form-group">
                                <label for="remarks">Place</label>
                                <input type="text" name="place2" value="{{ $level2->place }}" class="form-control h-1"/>
                            </div>
                            <div class="form-group">
                                <label for="remarks2">Remarks</label>
                                <textarea name="description2" id="description2" cols="14" rows="5" class="form-control">{{ $level2->description }}</textarea>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <span style="font-size: 13px">Selected</span>
                                <input type="radio" name="remarks2" class="radio ml-2 mr-4" value="Selected" id="male" @if($level2->status == 'Selected') checked @endif>
                                <span style="font-size: 13px">Recommended</span>
                                <input type="radio" name="remarks2" class="radio ml-1 mr-4" value="Recommended" class="ml-2" id="female" @if($level2->status == 'Recommended') checked @endif>
                                <span style="font-size: 13px">On Hold</span>
                                <input type="radio" name="remarks2" class="radio ml-1 mr-4" value="On Hold" class="ml-2" id="female" @if($level2->status == 'On Hold') checked @endif>
                                <span style="font-size: 13px">Rejected</span>
                                <input type="radio" name="remarks2" class="radio ml-1 mr-4" value="Rejected" class="ml-2" id="female" @if($level2->status == 'Rejected') checked @endif>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        @else
            <!-- Form for Level 2 when data is not present -->
            <div class="col-md-6">
                <div class="card bg-light mb-3">
                    <div class="card-header bg-white border-bottom-grey text-capitalize">
                        <h3 class="text-center">Interview Level 2 (System Test)</h3>
                    </div>
                    <div class="card-body">
                        <x-form id="remarks-form2">
                            <div class="form-group">
                                <label for="remarks">Place</label>
                                <input type="text" name="place2" class="form-control h-1"/>
                            </div>
                            <div class="form-group">
                                <label for="remarks2">Remarks</label>
                                <textarea name="description2" id="description2" cols="14" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <span style="font-size: 13px">Selected</span>
                                <input type="radio" name="remarks2" class="radio ml-2 mr-4" value="Selected" id="male" >
                                <span style="font-size: 13px">Recommended</span>
                                <input type="radio" name="remarks2" class="radio ml-1 mr-4" value="Recommended" class="ml-2" id="female" >
                                <span style="font-size: 13px">On Hold</span>
                                <input type="radio" name="remarks2" class="radio ml-1 mr-4" value="On Hold" class="ml-2" id="female" >
                                <span style="font-size: 13px">Rejected</span>
                                <input type="radio" name="remarks2" class="radio ml-1 mr-4" value="Rejected" class="ml-2" id="female" >
                            </div>
                            <div class="form-group text-center mb-0">
                                <button class="btn btn-primary btn-sm" id="save-remarks-form2" value="level2">Submit</button>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>



<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker('refresh');

        $('#save-remarks-form').click(function () {
            var l=$(this).val();
            url = "{{ route('interview.store', ['userId' => $userid]) }}&level=" + l;

            $.easyAjax({
                url: url,
                container: '#remarks-form',
                type: "post",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-remarks-form",
                data: $('#remarks-form').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else {
                            window.location.href = response.redirectUrl;
                        }
                    }
                }
            });
        });

        init(RIGHT_MODAL);
    });
</script>

<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker('refresh');

        $('#save-remarks-form2').click(function () {
            var l=$(this).val();

            url = "{{ route('interview.store', ['userId' => $userid]) }}&level=" + l;

            $.easyAjax({
                url: url,
                container: '#remarks-form2',
                type: "post",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-remarks-form2",
                data: $('#remarks-form2').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else {
                            window.location.href = response.redirectUrl;
                        }
                    }
                }
            });
        });

        init(RIGHT_MODAL);
    });
    
</script>
