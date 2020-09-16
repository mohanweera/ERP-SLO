@extends('admin::layouts.master')

@section('page_content')
    <div class="row">
        <div class="col-md-12 text-md">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="header-title">Import Permissions</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">You can import permissions which has been prepared by developer through this import window.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $disabledStatus = "disabled";
                            if(isset($permSystems) && is_array($permSystems) && count($permSystems)>0)
                            {
                                $disabledStatus = "";
                                ?>
                                <div class="form-group">
                                    <label for="system">Select Permission System To Import Permissions From</label>
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

                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 30px;">
                                <button type="button" <?php echo $disabledStatus; ?> class="btn btn-success btn-add-row" onclick="return loadSystem();">Load Permissions</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadSystem()
        {
            let system = document.getElementById("system").value;

            if(system != "")
            {
                window.location = "<?php echo $formSubmitUrl; ?>/"+system;
            }
        }
    </script>
@endsection

@section('page_css')
@endsection

@section('page_js')
@endsection
