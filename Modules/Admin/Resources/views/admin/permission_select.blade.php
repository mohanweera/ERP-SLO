@extends('admin::layouts.master')

@section('page_content')
    <div class="row">
        <div class="col-md-12 text-md">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="header-title">Grant Permissions</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">You can grant permissions to administrators apart from their admin role.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="system">Select Administrator</label>
                                <hr class="mt-1 mb-2">
                                <input type="text" class="form-control" name="admin">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <?php
                            $disabledStatus = "disabled";
                            if(isset($permSystems) && is_array($permSystems) && count($permSystems)>0)
                            {
                                $disabledStatus = "";
                                ?>
                                <div class="form-group">
                                    <label for="system">Select Permission System To Import Permissions From</label>
                                    <hr class="mt-1 mb-2">
                                    <select name="system" id="system" class="form-control">
                                        <option value="">Select System</option>
                                        <?php
                                        foreach ($permSystems as $system)
                                        {
                                            ?>
                                            <option value="<?php echo $system["admin_perm_system_id"]; ?>"><?php echo $system["system_name"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group" style="margin-top: 45px;">
                                <button type="button" <?php echo $disabledStatus; ?> class="btn btn-success btn-add-row" onclick="return loadAdmin();">Load Permissions</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="admin_id">
    </div>

    <script>
        let admin_role_ms = null;
        window.onload = function()
        {
            admin_role_ms = $("input[name='admin']").magicSuggest({
                allowFreeEntries: false,
                maxSelection:1,
                data: "/admin/admin/search_data",
                dataUrlParams:{"_token":"{{ csrf_token() }}"}
            });

            $(admin_role_ms).on('selectionchange', function(){
                let admins = this.getValue();

                $("input[name='admin_id']").val(admins[0]);
            });
        };

        function loadAdmin()
        {
            let admin_id = $("input[name='admin_id']").val();
            let system = $("select[name='system']").val();

            if(admin_id !== "" && system !== "")
            {
                window.location = "<?php echo $formSubmitUrl; ?>/"+admin_id+"/"+system;
            }
            else
            {
                let errorText=[];
                let errorData=[];

                errorText.push('Please select both admin & system to proceed with the permission management.');

                errorData.status="warning";
                errorData.notify=errorText;

                showNotifications(errorData)
            }
        }
    </script>
@endsection

@section('page_css')
@endsection

@section('page_js')
@endsection
