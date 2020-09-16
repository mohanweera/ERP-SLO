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
                                <h4 class="header-title">Permission System: <?php echo $permissionSystem["system_name"]; ?> | Grant Permissions</h4>
                                <input type="hidden" name="admin_perm_system_id" value="<?php echo $permissionSystem["admin_perm_system_id"]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted">You can grant permissions to administrators apart from their admin role using this window.</p>

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

                                            <input type="hidden" name="inv_rev_status" value="1">
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $systemSlug = $permissionSystem["system_slug"];

                                $modules = $permissionSystem["modules"];
                                $systemId = $permissionSystem["id"];
                                $systemSlug = $permissionSystem["system_slug"];
                                ?>
                                <input type="hidden" name="system_id[]" value="<?php echo $systemId; ?>">
                                <?php
                                if(is_array($modules) && count($modules)>0)
                                {
                                    foreach ($modules as $modKey => $module)
                                    {
                                        $modName = $module["name"];
                                        $modSlug = $module["slug"];
                                        $moduleId = $module["module_id"];
                                        $groups = $module["groups"];
                                        ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="modules[]" value="<?php echo $systemId; ?>_<?php echo $modKey; ?>">
                                                        <div class="row">
                                                            <div class="col-md-11">
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <input type="checkbox" name="<?php echo $systemId; ?>_<?php echo $modKey; ?>_checked" value="1" onchange="return onChangeModule(<?php echo $systemId; ?>, <?php echo $modKey; ?>, false);">
                                                                        </span>
                                                                    </div>
                                                                    <input type="text" value="<?php echo $modName; ?>" class="form-control" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="btn btn-info btn-sm" id="expand_mod_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>" onclick="return expandMod('<?php echo $systemSlug; ?>', '<?php echo $modSlug; ?>');">
                                                                    <span class="fa fa-chevron-down"></span>
                                                                </div>
                                                                <div class="btn btn-info btn-sm" id="collapse_mod_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>" onclick="return collapseMod('<?php echo $systemSlug; ?>', '<?php echo $modSlug; ?>');" style="display: none;">
                                                                    <span class="fa fa-chevron-up"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="clearfix" style="display: none;" id="mod_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>">
                                                <hr>
                                                <?php
                                                if(is_array($groups) && count($groups)>0)
                                                {
                                                    foreach ($groups as $groupKey => $group)
                                                    {
                                                        $groupName = $group["name"];
                                                        $groupSlug = $group["slug"];
                                                        $groupId = $group["group_id"];
                                                        $permissions = $group["permissions"];
                                                        ?>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="col-md-12">
                                                                    <input type="hidden" name="<?php echo $systemId; ?>_<?php echo $modKey; ?>_groups[]" value="<?php echo $groupKey; ?>">
                                                                    <div class="row">
                                                                        <div class="col-md-11">
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                                                <span class="input-group-text">
                                                                                                                    <input type="checkbox" name="<?php echo $systemId; ?>_<?php echo $modKey."_".$groupKey; ?>_checked" value="1" onchange="return onChangeGroup(<?php echo $systemId; ?>, <?php echo $modKey; ?>, <?php echo $groupKey; ?>, false);">
                                                                                                                </span>
                                                                                </div>
                                                                                <input type="text" value="<?php echo $groupName; ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <div class="btn btn-info btn-sm" id="expand_group_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>_<?php echo $groupSlug; ?>" onclick="return expandGroup('<?php echo $systemSlug; ?>', '<?php echo $modSlug; ?>', '<?php echo $groupSlug; ?>');">
                                                                                <span class="fa fa-chevron-down"></span>
                                                                            </div>
                                                                            <div class="btn btn-info btn-sm" id="collapse_group_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>_<?php echo $groupSlug; ?>" onclick="return collapseGroup('<?php echo $systemSlug; ?>', '<?php echo $modSlug; ?>', '<?php echo $groupSlug; ?>');" style="display: none;">
                                                                                <span class="fa fa-chevron-up"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix" style="display: none;" id="group_<?php echo $systemSlug; ?>_<?php echo $modSlug; ?>_<?php echo $groupSlug; ?>">
                                                                    <hr>
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                        <?php
                                                                        if(is_array($permissions) && count($permissions)>0)
                                                                        {
                                                                            foreach ($permissions as $permKey => $permission)
                                                                            {
                                                                                $permName = $permission["name"];
                                                                                $hash = $permission["hash"];
                                                                                $action = $permission["action"];
                                                                                $permId = $permission["perm_id"];

                                                                                $checked = "";
                                                                                $disabled = "";
                                                                                if(in_array($permId, $currPermissions))
                                                                                {
                                                                                    $checked = "checked";
                                                                                    $disabled = "disabled";
                                                                                }
                                                                                ?>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <input type="hidden" name="<?php echo $systemId; ?>_perm_id[]" value="<?php echo $permId; ?>">
                                                                                        <input type="hidden" name="<?php echo $systemId; ?>_<?php echo $modKey."_".$groupKey; ?>_permissions[]" value="<?php echo $permKey; ?>">
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text">
                                                                                                    <input type="checkbox" name="<?php echo $systemId; ?>_<?php echo $modKey."_".$groupKey."_".$permKey; ?>_checked" value="1" onchange="return onChangePerm(<?php echo $systemId; ?>, <?php echo $modKey; ?>, <?php echo $groupKey; ?>, false);" <?php echo $checked; ?> <?php echo $disabled; ?> class="perm_<?php echo $systemId; ?>_<?php echo $permId; ?>_checked">
                                                                                                </span>
                                                                                            </div>
                                                                                            <input type="text" value="<?php echo $permName; ?>" class="form-control" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <script>
                                                                                    $(document).ready(function () {

                                                                                        onChangePerm(<?php echo $systemId; ?>, <?php echo $modKey; ?>, <?php echo $groupKey; ?>, true);
                                                                                    });
                                                                                </script>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
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

        function expandMod(systemSlug, modSlug)
        {
            $("#mod_"+systemSlug+"_"+modSlug).slideDown(300);
            $("#expand_mod_"+systemSlug+"_"+modSlug).slideUp(300, function (){

                $("#collapse_mod_"+systemSlug+"_"+modSlug).slideDown(1);
            });
        }

        function collapseMod(systemSlug, modSlug)
        {
            $("#mod_"+systemSlug+"_"+modSlug).slideUp(300);
            $("#collapse_mod_"+systemSlug+"_"+modSlug).slideUp(300, function (){

                $("#expand_mod_"+systemSlug+"_"+modSlug).slideDown(1);
            });
        }

        function expandGroup(systemSlug, modSlug, groupSlug)
        {
            $("#group_"+systemSlug+"_"+modSlug+"_"+groupSlug).slideDown(300);
            $("#expand_group_"+systemSlug+"_"+modSlug+"_"+groupSlug).slideUp(300, function (){

                $("#collapse_group_"+systemSlug+"_"+modSlug+"_"+groupSlug).slideDown(1);
            });
        }

        function collapseGroup(systemSlug, modSlug, groupSlug)
        {
            $("#group_"+systemSlug+"_"+modSlug+"_"+groupSlug).slideUp(300);
            $("#collapse_group_"+systemSlug+"_"+modSlug+"_"+groupSlug).slideUp(300, function (){

                $("#expand_group_"+systemSlug+"_"+modSlug+"_"+groupSlug).slideDown(1);
            });
        }

        function updatePermissions(systemId)
        {
            let perms = $("input[name='"+systemId+"_perm_id[]']");

            if(perms.length>0)
            {
                let systemPerms = [];
                $(perms).each(function (pI, pElem) {

                    let perm_id = $(this).val();
                    if($(".perm_"+systemId+"_"+perm_id+"_checked").prop("checked"))
                    {
                        systemPerms.push(perm_id);
                    }
                });

                permissions[systemId] = systemPerms;
            }
        }

        function onChangeModule(systemId, module, initial)
        {
            let groupElems = $("input[name='"+systemId+"_"+module+"_groups[]']");
            if($("input[name='"+systemId+"_"+module+"_checked']").prop("checked"))
            {
                if(groupElems.length>0)
                {
                    $(groupElems).each(function (index, gElem) {

                        let group = $(this).val();
                        $("input[name='"+systemId+"_"+module+"_"+group+"_checked']").prop("checked", true);

                        let permElems = $("input[name='"+systemId+"_"+module+"_"+group+"_permissions[]']");
                        if(permElems.length>0)
                        {
                            $(permElems).each(function (index, pElem) {

                                let perm = $(this).val();
                                $("input[name='"+systemId+"_"+module+"_"+group+"_"+perm+"_checked']").not("input[disabled='disabled']").prop("checked", true);
                            });
                        }
                    });
                }
            }
            else
            {
                if(groupElems.length>0)
                {
                    $(groupElems).each(function (index, gElem) {

                        let group = $(this).val();
                        $("input[name='"+systemId+"_"+module+"_"+group+"_checked']").prop("checked", false);

                        let permElems = $("input[name='"+systemId+"_"+module+"_"+group+"_permissions[]']");
                        if(permElems.length>0)
                        {
                            $(permElems).each(function (index, pElem) {

                                let perm = $(this).val();
                                $("input[name='"+systemId+"_"+module+"_"+group+"_"+perm+"_checked']").not("input[disabled='disabled']").prop("checked", false);
                            });
                        }
                    });
                }
            }

            if(!initial)
            {
                updatePermissions(systemId);
            }
        }

        function onChangeGroup(systemId, module, group, initial)
        {
            if($("input[name='"+systemId+"_"+module+"_"+group+"_checked']").prop("checked"))
            {
                let permElems = $("input[name='"+systemId+"_"+module+"_"+group+"_permissions[]']");
                if(permElems.length>0)
                {
                    $(permElems).each(function (index, pElem) {

                        let perm = $(this).val();
                        $("input[name='"+systemId+"_"+module+"_"+group+"_"+perm+"_checked']").not("input[disabled='disabled']").prop("checked", true);
                    });
                }
            }
            else
            {
                let permElems = $("input[name='"+systemId+"_"+module+"_"+group+"_permissions[]']");
                if(permElems.length>0)
                {
                    $(permElems).each(function (index, pElem) {

                        let perm = $(this).val();
                        $("input[name='"+systemId+"_"+module+"_"+group+"_"+perm+"_checked']").not("input[disabled='disabled']").prop("checked", false);
                    });
                }
            }
            triggerModuleCheck(systemId, module);

            if(!initial)
            {
                updatePermissions(systemId);
            }
        }

        function onChangePerm(systemId, module, group, initial)
        {
            triggerGroupCheck(systemId, module, group);

            if(!initial)
            {
                updatePermissions(systemId);
            }
        }

        function triggerGroupCheck(systemId, module, group)
        {
            let groupChecked = false;

            //at least one permission is checked, then whole module will be displayed as checked
            let permElems = $("input[name='"+systemId+"_"+module+"_"+group+"_permissions[]']");
            if(permElems.length>0)
            {
                $(permElems).each(function (index, pElem) {

                    let perm = $(this).val();
                    if($("input[name='"+systemId+"_"+module+"_"+group+"_"+perm+"_checked']").prop("checked"))
                    {
                        groupChecked = true;
                        return false;
                    }
                });
            }

            $("input[name='"+systemId+"_"+module+"_"+group+"_checked']").prop("checked", groupChecked);
            triggerModuleCheck(systemId, module);
        }

        function triggerModuleCheck(systemId, module)
        {
            let moduleChecked = false;

            //at least one group is checked, then whole module will be displayed as checked
            let groupElems = $("input[name='"+systemId+"_"+module+"_groups[]']");
            if(groupElems.length>0)
            {
                $(groupElems).each(function (index, gElem) {

                    let group = $(this).val();
                    if($("input[name='"+systemId+"_"+module+"_"+group+"_checked']").prop("checked"))
                    {
                        moduleChecked = true;
                        return false;
                    }
                });
            }

            $("input[name='"+systemId+"_"+module+"_checked']").prop("checked", moduleChecked);
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
