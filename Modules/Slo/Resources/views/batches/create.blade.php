@extends('slo::layouts.master')
@section('content')
@if(isset($batchData))
<script>
    $(document).ready(function () {
        $("#course_id").val("{{$batchData->course_id}}");
        $("#batch_type").val("{{$batchData->batch_type}}");

    });
</script>
@endif
<div class="container-fluid behind">

<form  class="form-label-left input_mask needs-validation" method="post" action="" id="batchAddForm" novalidate>
@csrf
<input type="hidden" name="bId" id="bId" value="{{$batchData->batch_id ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="header-title">Add New Batch</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            <a href="/batchList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
                            <a href="/batchTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_id">Select Course</label>
                                <select class="form-control " name="course_id" id="course_id" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $data)
                                    <option value="{{$data->course_id}}">{{$data->course_name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="batch_type">Batch Type</label>
                                <select class="form-control " name="batch_type" id="batch_type" required value="{{$batchData->batch_type ?? ''}}">
                                <option value="">Select Batch Type</option>
                                @foreach($batchTypes as $batchTypes)
                                    <option value="{{$batchTypes->id}}">{{$batchTypes->description}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        @if(isset($batchData))
                        <div class="col-md-2" id="batchCode">
                            <div class="form-group">
                                <label for="batch_type">Batch Code</label>
                                <input type="text" class="form-control" name="batch_code" id="batch_code" value="{{$batchData->batch_code ?? ''}}" readonly>
                            </div>
                        </div>
                        @else
                        <div class="col-md-2" id="batchCode" style="display:none">
                            <div class="form-group">
                                <label for="batch_type">Batch Code</label>
                                <input type="text" class="form-control" name="batch_code" id="batch_code" value="{{$batchData->batch_code ?? ''}}" readonly>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_name">Batch Name</label>
                                <input type="text" class="form-control" name="batch_name" id="batch_name" placeholder="Batch Name" required value="{{$batchData->batch_name ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_student">Max Student</label>
                                <input type="text" class="form-control" name="max_student" id="max_student" placeholder="Max Student" required value="{{$batchData->max_student ?? ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_start_date">Batch Start Date</label>
                                <input type="date" class="form-control" name="batch_start_date" id="batch_start_date" required value="{{$batchData->batch_start_date ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_end_date">Batch End Date</label>
                                <input type="date" class="form-control" name="batch_end_date" id="batch_end_date" required value="{{$batchData->batch_end_date ?? ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_start_date">Registrations Start Date</label>
                                <input type="date" class="form-control" name="rs_date" id="rs_date" required value="{{$batchData->RS_Date ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_end_date">Registration End Date</label>
                                <input type="date" class="form-control" name="re_date" id="re_date" required value="{{$batchData->RE_Date ?? ''}}">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-1 mb-2">

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-add-row" id="aa">Save</button>
                            <button class="btn btn-dark" type="reset">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading" style="display:none">
            <div class="card-body" align="center">
            <div class="spinner-grow text-muted"></div>
            <div class="spinner-grow text-primary"></div>
            <div class="spinner-grow text-success"></div>
            <div class="spinner-grow text-info"></div>
            <div class="spinner-grow text-warning"></div>
            <div class="spinner-grow text-danger"></div>
            <div class="spinner-grow text-secondary"></div>
            <div class="spinner-grow text-dark"></div>
            <div class="spinner-grow text-light"></div>
            </div>
            </div>
        </div>
    </div>

</form>
</div>
<script>

  //$("#batchAddForm").load();

</script>
@endsection
