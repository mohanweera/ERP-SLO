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

                    <div class="form-group">
                        <div class="form-group row">
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
                            <!-- <div class="col-sm-4"><input type="number" name="" value="" class="form-control" id="" placeholder="Total" ></div>               -->
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
@endsection
