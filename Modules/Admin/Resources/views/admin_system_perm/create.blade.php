@extends('admin::layouts.master')

@section('page_content')
    <form action="javascript:;" id="create_form">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php
                                if($formMode == "add")
                                {
                                    ?>
                                    <h4 class="header-title">Add New Group Permission</h4>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <h4 class="header-title">Edit Group Permission</h4>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <?php
                                    if($formMode=="edit")
                                    {
                                        if(isset($urls["addUrl"]))
                                        {
                                            ?>
                                            <a href="{{$urls["addUrl"]}}">
                                                <div class="btn btn-info btn-sm"><span class="fa fa-plus"></span> Add New</div>
                                            </a>
                                            <?php
                                        }
                                    }

                                    if(isset($urls["listUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["listUrl"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-list"></span> List Group Permissions</div>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Permission System</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" value="<?php echo $record["permissionSystem"]["system_name"]; ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Permission Module</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" value="<?php echo $record["permissionModule"]["module_name"]; ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permission Group</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" value="<?php echo $record["permissionGroup"]["group_name"]; ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permission Title</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="permission_title" placeholder="Permission Title" value="<?php echo $record["permission_title"]; ?>">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permission Action</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="permission_action" placeholder="Permission Action" value="<?php echo $record["permission_action"]; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Enable/Disable Permission Group<span
                                            class="text-danger">*</span></label>
                                    <select name="permission_status" class="form-control" onchange="return onStatusChange(this);">
                                        <option value="1" <?php if ($record["permission_status"] == "1") { ?> selected="selected" <?php } ?>>
                                            Enable
                                        </option>
                                        <option value="0" <?php if ($record["permission_status"] == "0") { ?> selected="selected" <?php } ?>>
                                            Disable
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-success btn-add-row">Save</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>

                        <div class="row" id="disabled_reason" style="display: none;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Reason for Disabling <span class="text-danger">*</span></label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="disabled_reason" placeholder="Disabled Reason" value="<?php echo $record["disabled_reason"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        window.onload = function()
        {
            submitCreateForm();
        };

        function onStatusChange(elem)
        {
            if($(elem).val() !== "1")
            {
                $("#disabled_reason").slideDown(300);
            }
            else
            {
                $("#disabled_reason").slideUp(300);
            }
        }

        function submitCreateForm()
        {
            $('#create_form').ajaxForm({
                url			: "<?php if(isset($formSubmitUrl)){ echo URL::to($formSubmitUrl); } ?>",
                type		: "POST",
                dataType	: "json",
                beforeSubmit: validateForm,
                success		: serverResponse,
                error		: onError
            });
        }

        function validateForm(formData, jqForm)
        {
            let form = jqForm[0];

            let errors=0;
            let errorText=[];

            let permission_title=form.permission_title.value;
            let permission_action=form.permission_action.value;

            errorText.push("<strong> <span class='glyphicon glyphicon-warning-sign'></span> Following errors occurred while submitting the form</strong><br/>");

            if(permission_title === "")
            {
                errors++;
                errorText.push('Permission Title Required.');
            }

            if(permission_action === "")
            {
                errors++;
                errorText.push('Permission Action Required.');
            }

            if(errors > 0)
            {
                let errorData=[];
                errorData.status="warning";
                errorData.notify=errorText;

                showNotifications(errorData);

                return false;
            }
            else
            {
                //show preloader
                showPreloader($('#create_form'), true);

                //disable form submit
                $('#create_form button[type="submit"]').attr("disabled", "disabled");
                return true;
            }
        }

        function serverResponse(responseText)
        {
            //Hide preloader
            hidePreloader($('#create_form'));
            $('#create_form button[type="submit"]').removeAttr("disabled");

            <?php
            if($formMode === "add")
            {
            ?>
                if(responseText.notify.status && responseText.notify.status == "success")
                {
                    $("#create_form").trigger("reset");
                }
                <?php
            }
            ?>

            showNotifications(responseText.notify)
        }

        function onError()
        {
            //Hide preloader
            hidePreloader($("#create_form"));
            $('#create_form button[type="submit"]').removeAttr("disabled");

            let errorText=[];
            let errorData=[];

            errorText.push('Something went wrong. Please try again.');

            errorData.status="warning";
            errorData.notify=errorText;

            showNotifications(errorData)
        }
    </script>
@endsection

@section('page_css')
@endsection

@section('page_js')
@endsection
