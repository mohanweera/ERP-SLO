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
                                <h4 class="header-title">Administrator Role | {{$record["role_name"]}}</h4>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <?php
                                    if(isset($urls["historyUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["historyUrl"].$record["id"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-list"></span> View Change History</div>
                                        </a>
                                        <?php
                                    }

                                    if(isset($urls["editUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["editUrl"].$record["id"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-edit"></span> Edit</div>
                                        </a>
                                        <?php
                                    }

                                    if(isset($urls["addUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["addUrl"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-plus"></span> Add New</div>
                                        </a>
                                        <?php
                                    }

                                    if(isset($urls["listUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["listUrl"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-list"></span> List Administrator Roles</div>
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
                            <div class="col-md-12">
                                <?php
                                if(is_array($systemPermissions) && count($systemPermissions)>0)
                                {
                                    ?>
                                    <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                <?php
                                                $i=0;
                                                foreach ($systemPermissions as $system)
                                                {
                                                    $i++;

                                                    $active = "";
                                                    if($i == 1)
                                                    {
                                                        $active = "active";
                                                    }
                                                    $systemSlug = $system["system_slug"];
                                                    ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link <?php echo $active; ?>" id="custom-tabs-<?php echo $systemSlug ?>-tab" data-toggle="pill" href="#custom-tabs-<?php echo $systemSlug ?>" role="tab" aria-controls="custom-tabs-<?php echo $systemSlug ?>" aria-selected="true"><?php echo $system["system_name"] ?></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                                <?php
                                                $i=0;
                                                foreach ($systemPermissions as $system)
                                                {
                                                    $i++;

                                                    $active = "";
                                                    if($i == 1)
                                                    {
                                                        $active = "show active";
                                                    }

                                                    $modules = $system["modules"];
                                                    $curr_permissions = $system["curr_permissions"];
                                                    $systemId = $system["id"];
                                                    $systemSlug = $system["system_slug"];
                                                    ?>
                                                    <div class="tab-pane fade <?php echo $active; ?>" id="custom-tabs-<?php echo $systemSlug ?>" role="tabpanel" aria-labelledby="custom-tabs-<?php echo $systemSlug ?>-tab">
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
                                                                                                    <input type="checkbox" name="<?php echo $systemId; ?>_<?php echo $modKey; ?>_checked" value="1">
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
                                                                                                            <input type="checkbox" name="<?php echo $systemId; ?>_<?php echo $modKey."_".$groupKey; ?>_checked" value="1">
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
                                                                                                                if(in_array($permId, $curr_permissions))
                                                                                                                {
                                                                                                                    $checked = "checked";
                                                                                                                }
                                                                                                                ?>
                                                                                                                <div class="row">
                                                                                                                    <div class="col-md-12">
                                                                                                                        <input type="hidden" name="<?php echo $systemId; ?>_perm_id[]" value="<?php echo $permId; ?>">
                                                                                                                        <input type="hidden" name="<?php echo $systemId; ?>_<?php echo $modKey."_".$groupKey; ?>_permissions[]" value="<?php echo $permKey; ?>">
                                                                                                                        <div class="input-group mb-3">
                                                                                                                            <div class="input-group-prepend">
                                                                                                                                <span class="input-group-text">
                                                                                                                                    <input type="checkbox" name="<?php echo $systemId; ?>_<?php echo $modKey."_".$groupKey."_".$permKey; ?>_checked" value="1" <?php echo $checked; ?> class="perm_<?php echo $systemId; ?>_<?php echo $permId; ?>_checked">
                                                                                                                                </span>
                                                                                                                            </div>
                                                                                                                            <input type="text" value="<?php echo $permName; ?>" class="form-control" readonly>
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
                                                    <?php
                                                }
                                                ?>
                                            </div>
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
                                    <label>Enable/Disable Administrator Role<span
                                            class="text-danger">*</span></label>
                                    <select name="role_status" class="form-control">
                                        <option value="1" <?php if ($record["role_status"] == "1") { ?> selected="selected" <?php } ?>>
                                            Enable
                                        </option>
                                        <option value="0" <?php if ($record["role_status"] == "0") { ?> selected="selected" <?php } ?>>
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
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
    $admin_role_id = [];
    if(isset($record["allowed_roles"]))
    {
        $admin_role_id=$record["allowed_roles"];
    }
    ?>
    <script>
        let admin_role_id_ms=null;
        let permissions={};

        window.onload = function()
        {
            submitCreateForm();

            admin_role_id_ms = $("input[name='allowed_roles']").magicSuggest({
                allowFreeEntries: false,
                maxSelection:999,
                data: "/admin/admin_role/search_data",
                dataUrlParams:{"_token":"{{ csrf_token() }}"},
                value:<?php echo json_encode($admin_role_id) ?>,
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
    </script>
@endsection

@section('page_css')
@endsection

@section('page_js')
@endsection
