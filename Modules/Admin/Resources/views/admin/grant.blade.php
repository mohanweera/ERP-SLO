@extends('admin::layouts.master')

@section('page_content')
    <form action="javascript:;" id="create_form">
        @csrf
        <div class="row">
            <div class="col-md-12 text-md">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                if($invRevStatus == "1")
                                {
                                    ?>
                                    <h4 class="header-title">Permission System: <?php echo $permissionSystem["system_name"]; ?> | Grant Permissions</h4>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <h4 class="header-title">Permission System: <?php echo $permissionSystem["system_name"]; ?> | Revoke Permissions</h4>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if($invRevStatus == "1")
                                {
                                    ?>
                                    <p class="text-muted">You can grant permissions to administrators apart from their admin role using this window.</p>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <p class="text-muted">You can revoke permissions of administrators apart from their admin role using this window.</p>
                                    <?php
                                }
                                ?>

                                <div class="card">
                                    <div class="card-body">
                                        <script>
                                            function onChangePeriod(elem)
                                            {
                                                let period = $(elem).val();

                                                if(period === "temporary")
                                                {
                                                    $("#period").slideDown(300);
                                                }
                                                else
                                                {
                                                    $("#period").slideUp(300);
                                                }
                                            }
                                        </script>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Administrator</label>
                                                    <hr class="mt-1 mb-2">
                                                    <input type="text" disabled value="<?php echo $admin["name"]; ?>" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label>Date Period <span class="text-danger">*</span></label>
                                                    <hr class="mt-1 mb-2">
                                                    <select name="period" class="form-control" onchange="return onChangePeriod(this);">
                                                        <option value="permanent">Permanent</option>
                                                        <option value="temporary">Temporarily</option>
                                                    </select>
                                                </div>

                                                <div class="row" id="period" style="display:none;">
                                                    <div class="col-md-6">
                                                        <input type="text" name="valid_from" class="form-control default-date-picker" placeholder="Valid From" data-date-start-date="0d">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="valid_till" class="form-control default-date-picker" placeholder="Valid Till" data-date-start-date="0d">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Remarks <span class="text-danger">*</span></label>
                                                    <hr class="mt-1 mb-2">
                                                    <textarea name="remarks" class="form-control" style="min-height: 137px;"></textarea>
                                                </div>
                                            </div>

                                            <input type="hidden" name="inv_rev_status" value="<?php echo $invRevStatus; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        $systemSlug = $permissionSystem["system_slug"];

                                        $modules = $permissionSystem["modules"];
                                        $systemId = $permissionSystem["id"];
                                        $systemSlug = $permissionSystem["system_slug"];
                                        ?>
                                        <input type="hidden" name="system_id[]" value="<?php echo $systemId; ?>">
                                        <table class="table table-bordered fixed_header">
                                            <thead>
                                            <tr>
                                                <th>Permission Module</th>
                                                <th>Permission Group</th>
                                                <th>Permission</th>
                                                <th>In Role</th>
                                                <?php
                                                if($invRevStatus == "1")
                                                {
                                                    ?>
                                                    <th>Granted</th>
                                                    <th>Grant Permissions</th>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <th>Revoked</th>
                                                    <th>Revoked Permissions</th>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if(is_array($modules) && count($modules)>0)
                                                {
                                                    foreach ($modules as $modKey => $module)
                                                    {
                                                        $groups = $module["groups"];

                                                        $permsCountTotal = 0;
                                                        $groupsCount = count($module["groups"]);

                                                        if($groupsCount>0)
                                                        {
                                                            foreach ($groups as $groupKey => $group)
                                                            {
                                                                $permsCount = count($group["permissions"]);

                                                                $permsCountTotal+=$permsCount;
                                                            }
                                                        }

                                                        $modules[$modKey]["perms_total"]=$permsCountTotal;
                                                    }
                                                }

                                                if(is_array($modules) && count($modules)>0)
                                                {
                                                    $currMod = -1;
                                                    $currGrp = -1;
                                                    foreach ($modules as $modKey => $module)
                                                    {
                                                        $modName = $module["name"];
                                                        $modSlug = $module["slug"];
                                                        $moduleId = $module["module_id"];
                                                        $groups = $module["groups"];
                                                        $permsCountTotal = $module["perms_total"];

                                                        $groupsCount = count($groups);

                                                        if($groupsCount>0)
                                                        {
                                                            foreach ($groups as $groupKey => $group)
                                                            {
                                                                $groupName = $group["name"];
                                                                $groupSlug = $group["slug"];
                                                                $groupId = $group["group_id"];
                                                                $permissions = $group["permissions"];

                                                                $permsCount = count($permissions);

                                                                if($permsCount>0)
                                                                {
                                                                    foreach ($permissions as $permKey => $permission)
                                                                    {
                                                                        $permName = $permission["name"];
                                                                        $hash = $permission["hash"];
                                                                        $action = $permission["action"];
                                                                        $permId = $permission["perm_id"];

                                                                        $inAdmin = false;
                                                                        $adminChecked = "";
                                                                        $inAdminRole = false;
                                                                        $adminRoleChecked = "";
                                                                        if(in_array($permId, $adminPermissions))
                                                                        {
                                                                            $inAdmin = true;
                                                                            $adminChecked = "checked";
                                                                        }

                                                                        if(in_array($permId, $adminRolePermissions))
                                                                        {
                                                                            $inAdminRole = true;
                                                                            $adminRoleChecked = "checked";
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <?php
                                                                            if($currMod != $modKey)
                                                                            {
                                                                                $currMod = $modKey;
                                                                                ?>
                                                                                <td rowspan="<?php echo $permsCountTotal; ?>">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <?php echo $modName; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <?php
                                                                            if($currGrp != $groupKey)
                                                                            {
                                                                                $currGrp = $groupKey;
                                                                                ?>
                                                                                <td rowspan="<?php echo $permsCount; ?>">
                                                                                    <div style="display: block;" class="clearfix mod_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <?php echo $groupName; ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <td>
                                                                                <div style="display: block;" class="clearfix group_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>_<?php echo $groupSlug; ?>">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <?php echo $permName; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>
                                                                                <span class="input-group-text custom-control custom-checkbox">
                                                                                    <input class="custom-control-input" type="checkbox"<?php echo $adminRoleChecked; ?>>
                                                                                    <label class="custom-control-label perm-cb-label"></label>
                                                                                </span>
                                                                            </td>

                                                                            <td>
                                                                                <span class="input-group-text custom-control custom-checkbox">
                                                                                    <input class="custom-control-input" type="checkbox"<?php echo $adminChecked; ?>>
                                                                                    <label class="custom-control-label perm-cb-label"></label>
                                                                                </span>
                                                                            </td>

                                                                            <td>
                                                                                <?php
                                                                                if($invRevStatus == "1")
                                                                                {
                                                                                    if(!$inAdminRole)
                                                                                    {
                                                                                        if($inAdmin)
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, 1);" class="form-control mb-0">
                                                                                                    <option value="1">Grant</option>
                                                                                                    <option value="">Inherit</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, '');" class="form-control mb-0">
                                                                                                    <option value="">Inherit</option>
                                                                                                    <option value="1">Grant</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    else if($inAdminRole)
                                                                                    {
                                                                                        if($inAdmin)
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, 1);" class="form-control mb-0">
                                                                                                    <option value="1">Grant</option>
                                                                                                    <option value="">Inherit</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, '');" class="form-control mb-0">
                                                                                                    <option value="">Inherit</option>
                                                                                                    <option value="1">Grant</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        ?>
                                                                                        <p>This Permission has revoked</p>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    if(!$inAdminRole)
                                                                                    {
                                                                                        if($inAdmin)
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, 0);" class="form-control mb-0">
                                                                                                    <option value="0">Revoke</option>
                                                                                                    <option value="">Inherit</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, '');" class="form-control mb-0">
                                                                                                    <option value="">Inherit</option>
                                                                                                    <option value="0">Revoke</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    else if($inAdminRole)
                                                                                    {
                                                                                        if($inAdmin)
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, 0);" class="form-control mb-0">
                                                                                                    <option value="0">Revoke</option>
                                                                                                    <option value="">Inherit</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                            <div class="form-group mb-0">
                                                                                                <select onchange="return onPermissionChange(this, <?php echo $permId; ?>, '');" class="form-control mb-0">
                                                                                                    <option value="">Inherit</option>
                                                                                                    <option value="0">Revoke</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        ?>
                                                                                        <p>This Permission has granted.</p>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-add-row">Grant Permissions</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        let permissions={};
        let systemId = "<?php echo $systemId; ?>";

        window.onload = function()
        {
            submitCreateForm();

            let format = "yyyy-mm-dd";

            $("input[name='valid_from']").datepicker({
                format	: format,
                orientation: 'bottom'
            });

            $("input[name='valid_till']").datepicker({
                format	: format,
                orientation: 'bottom'
            });
        };

        function onPermissionChange(elem, permId, prevStatus)
        {
            let newStatus = $(elem).val();
            let key = getPermissionKey(permId);

            let perm = {};
            perm.perm_id = permId;
            perm.prev_tatus = prevStatus;
            perm.new_status = newStatus;

            if(permissions[systemId])
            {
                if(key !== null)
                {
                    if(prevStatus == newStatus)
                    {
                        permissions[systemId].splice(key, 1);
                    }
                    else
                    {
                        permissions[systemId][key]=perm;
                    }
                }
                else
                {
                    permissions[systemId].push(perm);
                }
            }
            else
            {
                permissions[systemId] = [];
                permissions[systemId].push(perm);
            }
        }

        function getPermissionKey(permId)
        {
            let key = null;
            if(permissions[systemId] && permissions[systemId].length>0)
            {
                $(permissions[systemId]).each(function (index, perm){

                    if(perm.perm_id === permId)
                    {
                        key = index;
                    }
                });
            }

            return key;
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

            let perms = JSON.stringify(permissions);
            formData.push({"name":"permissions", "value":perms});

            let errors=0;
            let errorText=[];

            let period=form.period.value;
            let valid_from=form.valid_from.value;
            let valid_till=form.valid_till.value;
            let remarks=form.remarks.value;

            errorText.push("<strong> <span class='glyphicon glyphicon-warning-sign'></span> Following errors occurred while submitting the form</strong><br/>");

            if(remarks === "")
            {
                errors++;
                errorText.push('Remarks Required.');
            }

            if(period === "temporary")
            {
                if(valid_from === "" || valid_till === "")
                {
                    errors++;
                    errorText.push('Please select Date Period.');
                }
            }

            if(!permissions[systemId] || permissions[systemId].length===0)
            {
                errors++;
                errorText.push('Permission change not detected.');
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
