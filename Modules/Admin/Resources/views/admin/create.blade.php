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
                                    <h4 class="header-title">Add New Administrator</h4>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <h4 class="header-title">Edit Administrator</h4>
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
                                            <div class="btn btn-info btn-sm"><span class="fa fa-list"></span> List Administrators</div>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Administrator Name</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="name" placeholder="Administrator Name" value="<?php echo $record["name"]; ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Administrator Email</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="email" placeholder="Administrator Email" value="<?php echo $record["email"]; ?>">
                                </div>
                            </div>

                            <?php
                            if($formMode === "add")
                            {
                                ?>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Administrator Password</label>
                                                <hr class="mt-1 mb-2">
                                                <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo $record["password"]; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <hr class="mt-1 mb-2">
                                                <input type="password" class="form-control" name="password_conf" placeholder="Confirm Password" value="<?php echo $record["password_conf"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Administrator Role</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="admin_role">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Allowed Roles To Handle</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="allowed_roles">
                                    <p class="text-muted">(You can allow user roles handle by this user)</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Disallowed Roles To Handle</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="disallowed_roles">
                                    <p class="text-muted">(You can overwrite from here which has been allowed from admin role)</p>
                                </div>
                            </div>

                            <?php
                            $superUser = request()->session()->get("super_user");
                            if($superUser)
                            {
                                ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Super User</label>
                                        <hr class="mt-1 mb-2">
                                        <select name="super_user" class="form-control">
                                            <option value="0" <?php if ($record["super_user"] == "0") { ?> selected="selected" <?php } ?>>
                                                Disable
                                            </option>
                                            <option value="1" <?php if ($record["super_user"] == "1") { ?> selected="selected" <?php } ?>>
                                                Enable
                                            </option>
                                        </select>
                                        <p class="text-muted">(Super user can by pass the IP barrier)</p>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
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
                                    <label>Enable/Disable Administrator <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" onchange="return onStatusChange(this);">
                                        <option value="1" <?php if ($record["status"] == "1") { ?> selected="selected" <?php } ?>>
                                            Enable
                                        </option>
                                        <option value="0" <?php if ($record["status"] == "0") { ?> selected="selected" <?php } ?>>
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

        <input type="hidden" name="admin_role_id" value="<?php echo $record["admin_role_id"]; ?>">
    </form>

    <?php
    $admin_role_id = [];
    if(isset($record["admin_role"]))
    {
        $admin_role_id[]=$record["admin_role"];
    }

    $allowed_roles = [];
    if(isset($record["allowed_roles"]))
    {
        $allowed_roles[]=$record["allowed_roles"];
    }

    $disallowed_roles = [];
    if(isset($record["disallowed_roles"]))
    {
        $disallowed_roles[]=$record["disallowed_roles"];
    }
    ?>
    <script>
        let admin_role_id_ms = null;
        let allowed_roles_ms = null;
        let disallowed_roles_ms = null;
        window.onload = function()
        {
            submitCreateForm();

            admin_role_id_ms = $("input[name='admin_role']").magicSuggest({
                allowFreeEntries: false,
                maxSelection:1,
                data: "/admin/admin_role/search_data",
                dataUrlParams:{"_token":"{{ csrf_token() }}"},
                value:<?php echo json_encode($admin_role_id) ?>,
            });

            allowed_roles_ms = $("input[name='allowed_roles']").magicSuggest({
                allowFreeEntries: false,
                maxSelection:999,
                data: "/admin/admin_role/search_data",
                dataUrlParams:{"_token":"{{ csrf_token() }}"},
                value:<?php echo json_encode($allowed_roles) ?>,
            });

            disallowed_roles_ms = $("input[name='disallowed_roles']").magicSuggest({
                allowFreeEntries: false,
                maxSelection:999,
                data: "/admin/admin_role/search_data",
                dataUrlParams:{"_token":"{{ csrf_token() }}"},
                value:<?php echo json_encode($disallowed_roles) ?>,
            });

            $(admin_role_id_ms).on('selectionchange', function(e,m){
                let admin_roles = this.getValue();

                $("input[name='admin_role_id']").val(admin_roles[0]);
            });
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

            let name=form.name.value;
            let email=form.email.value;
            let status=form.status.value;
            let disabled_reason=form.disabled_reason.value;

            errorText.push("<strong> <span class='glyphicon glyphicon-warning-sign'></span> Following errors occurred while submitting the form</strong><br/>");

            if(name === "")
            {
                errors++;
                errorText.push('Administrator Name Required.');
            }

            let emailRegExp=/^[\w\-\.\+]+\@[a-z A-Z 0-9\.\-]+\.[a-z A-Z 0-9]{2,4}$/;

            if(!email.match(emailRegExp))
            {
                errors++;
                errorText.push('Valid Email Required.');
            }

            <?php
            if($formMode === "add")
            {
                ?>
                let password=form.password.value;
                let password_conf=form.password_conf.value;

                if(password === "")
                {
                    errors++;
                    errorText.push('Password Required.');
                }

                if(password !== password_conf)
                {
                    errors++;
                    errorText.push('Both Passwords Should be matched.');
                }
                <?php
            }
            ?>

            if(status === "0")
            {
                if(disabled_reason === "")
                {
                    errors++;
                    errorText.push('Reason for Disabling Required.');
                }
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
