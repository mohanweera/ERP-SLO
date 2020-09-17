@extends('slo::layouts.master')
@section('content')
@if(isset($group))
<script>
    $(document).ready(function () {
        $("#course_id").val("{{$group->CourseID}}");
        $("#batch_id").val("{{$group->BatchID}}");
    });
</script>
@endif
<div class="container-fluid behind">
<form class="form-label-left input_mask needs-validation" method="post" action="#" id="addNewGroup" novalidate>
    @csrf
    <input type="hidden" name="gId" id="gId" value="{{$group->GroupID ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title">Add New Group</h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            <a href="/groupList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
                            <a href="/groupsTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="course">Select Course</label>
                        <select class="form-control " name="course_id" id="course_id" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                            <option value="{{$course->course_id}}">{{$course->course_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="loadBatch">
                        <label for="batch">Select Batch</label>
                        <select class="form-control " name="batch_id" id="batch_id" required>
                            <option value="">Select Batch</option>
                            @if(isset($group))
                            @foreach($batches as $batches)
                            <option value="{{$batches->batch_id}}">{{$batches->batch_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="description">Group Name</label>
                                <textarea name="gName" value="" class="form-control" id="gName" placeholder="Group Name" required="required" rows="2">{{$group->GroupName ?? ''}}</textarea>
                        </div>

                    <div class="form-group">
                        <div class="form-group row">
                            <div class="col-sm-4">
                            <label for="description">Semester</label>
                            <input type="number" min="1" max="9" name="semester"  class="form-control" id="semester" placeholder="0" required="required" value="{{$group->semester ?? ''}}"></div>
                            <div class="col-sm-4">
                            <label for="description">Year</label>
                            <input type="number"  min="1900" max="2099" name="year"  class="form-control" id="year" placeholder="YYYY" required="required" value="{{$group->year ?? ''}}"></div>
                            <!-- <div class="col-sm-4"><input type="number" name="" value="" class="form-control" id="" placeholder="Total" ></div>               -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Type</label>
                        <textarea name="type" value="" class="form-control" id="type" placeholder="Type" required="required" rows="2">{{$group->type ?? ''}}</textarea>
                    </div>
                    <!-- /.card-body -->
                    <hr class="mt-1 mb-2">

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-add-row">Save</button>
                            <button class="btn btn-dark" type="reset">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    </div>
</form>
</div>
<script>
$(document).ready(function(){
$("#course_id").change(function(){
    var course_id = $("#course_id").val();
    if(course_id !=''){
        $('#loadBatch').load('/select/batch2/' + course_id);
    }else{
        $('#loadBatch').load('/select/batch2/' + 0);
    }
    
});
});
</script>
@endsection
