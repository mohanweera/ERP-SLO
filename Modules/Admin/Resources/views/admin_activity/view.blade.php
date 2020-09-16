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
                                <h4 class="header-title">Admin Activity Data | {{$record["activity"]}}</h4>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <?php
                                    if(isset($urls["listUrl"]))
                                    {
                                        ?>
                                        <a href="{{$urls["listUrl"]}}">
                                            <div class="btn btn-info btn-sm"><span class="fa fa-list"></span> Go Back To Activity List</div>
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><strong>Administrator</strong>: <?php echo $admin["name"]; ?></h6>
                                                <h6><strong>Event</strong>: <?php echo $record["event"]; ?></h6>
                                                <h6><strong>Activity Model</strong>: <?php echo $record["activity_model_name"]; ?></h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><strong>Activity</strong>: <?php echo $record["activity"]; ?></h6>
                                                <h6><strong>Activity On</strong>: <?php echo $record["activity_at"]; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Old Data</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Field</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $oldData = $record["activity_old_data"];

                                        if(is_array($oldData) && count($oldData)>0)
                                        {
                                            foreach ($oldData as $field => $value)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $field; ?></td>
                                                    <td><?php echo $value; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>New Data</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Field</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $newData = $record["activity_new_data"];

                                        if(is_array($newData) && count($newData)>0)
                                        {
                                            foreach ($newData as $field => $value)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $field; ?></td>
                                                    <td><?php echo $value; ?></td>
                                                </tr>
                                                <?php
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
    </form>
@endsection

@section('page_css')
@endsection

@section('page_js')
@endsection
