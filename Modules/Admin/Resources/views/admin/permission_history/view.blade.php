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
                                <h4 class="header-title">Administrator Role | {{$admin["name"]}} | Changed Permissions</h4>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <?php
                                    if(isset($urls["listUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["listUrl"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-backward"></span> Go Back To List</div>
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
                                <div class="card">
                                    <div class="card-body">
                                        <h6><strong>Permission System</strong>: <?php echo $record["permission_system"]["system_name"]; ?></h6>
                                        <h6><strong>Remarks</strong>: <?php echo $record["remarks"]; ?></h6>
                                    </div>
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Granted Permissions</th>
                                            <th>Revoked Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Permission Group</th>
                                                        <th>Permission</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if(count($invokedPermissions)>0)
                                                        {
                                                            foreach ($invokedPermissions as $perm)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $perm["permission_group"]["group_name"]; ?></td>
                                                                    <td><?php echo $perm["permission_title"]; ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td colspan="2" class="text-center">
                                                                    <p class="p-3">There are no any permission grants in this history record.</p>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Permission Group</th>
                                                        <th>Permission</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if(count($revokedPermissions)>0)
                                                    {
                                                        foreach ($revokedPermissions as $perm)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $perm["permission_group"]["group_name"]; ?></td>
                                                                <td><?php echo $perm["permission_title"]; ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td colspan="2" class="text-center">
                                                                <p class="p-3">There are no any permission revokes in this history record.</p>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('page_css')
@endsection

@section('page_js')
@endsection
