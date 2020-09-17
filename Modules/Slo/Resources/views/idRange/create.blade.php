@extends('slo::layouts.master')
@section('content')
@if(isset($data))
<script>
    $(document).ready(function () {
        $("#course_id").val("{{$data->course_id}}");

    });
</script>
@endif
<div class="container-fluid behind">
<form class="form-label-left input_mask needs-validation" method="post" action="#" id="addNewIdRange" novalidate>
    @csrf
    <input type="hidden" name="iId" id="iId" value="{{$data->id ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title">Add New ID Range</h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            <a href="/idRangeList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
                            <a href="/idRangeTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="course_id">Select Course</label>
                        <select class="form-control " name="course_id" id="course_id" required data-dependent="start">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                            <option value="{{$course->course_id}}">{{$course->course_name}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <label for="description">Description</label>

                                <textarea name="description" value="" class="form-control" id="description" placeholder="Description" required="required" rows="2">{{$data->description ?? ''}}</textarea>

                            <!-- <div class="col-sm-4"><input type="number" name="" value="" class="form-control" id="" placeholder="Total" ></div>               -->
                        </div>
                        @if(!isset($data))
                        <div class="form-group">
                        <span class="btn badge badge-success" id="exportBut">Export Numbers</span>
                        <div id="showSelect" style="display:none">
                        <br/><label for="description">Numbers From ID Ranges</label>
                        <select class="form-control " name="ids" id="ids" required>
                        <option value="" data-start="0">Select IDs</option>
                        @foreach($ids as $ids)
                        <option value="{{$ids->id}}" data-start="{{$ids->last_id}}" data-end="{{$ids->end}}">{{$ids->last_id}} - {{$ids->end}}</option>
                        @endforeach
                        </select>
                        </div>
                        </div>
                        @endif
                    <div class="form-group">
                        <div class="form-group row" id="startend">
                            <div class="col-sm-4">
                            <input type="number" name="start"  class="form-control" id="start" placeholder="Start Number" required value="{{$start}}" min="{{$start}}">
                            </div>
                            <div class="col-sm-4">
                                @if(isset($data))
                                <input type="number" name="end"  class="form-control" id="end" placeholder="End Number" required="required" value="{{$data->end ?? ''}}"  readonly>
                                @else
                                <input type="number" name="end"  class="form-control" id="end" placeholder="End Number" required="required" value="{{$data->end ?? ''}}"  min="{{$start}}">
                                @endif
                            </div>
                        </div>
                        <div class="form-group row" id="startend2" style="display:none">
                            <div class="col-sm-4">
                            <input type="number" name="start2"  class="form-control" id="start2" placeholder="Start Number" required >
                            </div>
                            <div class="col-sm-4">
                                @if(isset($data))
                                <input type="number" name="end2"  class="form-control" id="end2" placeholder="End Number" required="required" value="{{$data->end ?? ''}}"  readonly>
                                @else
                                <input type="number" name="end2"  class="form-control" id="end2" placeholder="End Number" required="required" value="{{$data->end ?? ''}}" readonly>
                                @endif
                            </div>
                        </div>
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
    var x = $("#course_id").val();
    var y = $("#course_id").val();
    alert(x + y);
    $("#ids").change(function(){
    var start = $(this).find(':selected').data('start');
    var end = $(this).find(':selected').data('end');
    if(start == "0"){
        $("#startend2").hide();
        $("#startend").show();
    }else{
        $("#startend").hide();
        $("#startend2").show();
        $("#start2").val(start);
        $("#end2").val(end);
    }
    });
    $("#exportBut").click(function(){
       
        $("#showSelect").show();
        
    });
});
</script>
@endsection
