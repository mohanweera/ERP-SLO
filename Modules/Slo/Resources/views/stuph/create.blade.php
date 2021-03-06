@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
@if($count > 0)
<form class="form-label-left input_mask needs-validation" method="post" action="" id="uploadStd" novalidate>
    @csrf
    <input type="hidden" name="tId" id="tId" value="{{$batchData->id ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-10">

                            <h4 class="header-title">Upload Documents to {{$data->name_initials}}</h4>

                        </div>
                        <div class="col-sm-2">
                            <div class="float-right">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <input type="text" name="range_id" id="range_id" value="{{$data->range_id}}" style="display:none">
                    <input type="text" name="std_id" id="std_id" value="{{$data->student_id}}" style="display:none">
                    <div class="form-group">
                        <label for="course_id">Upload Categories</label>
                        <select class="form-control " name="cid" id="cid" required data-dependent="start">
                            <option value="">Upload Categories</option>
                            @foreach($ctg as $ctg)
                            <option value="{{$ctg->cat_code}}">{{$ctg->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="course_id">Upload Document Name</label>
                        <input type="text" name="d_name"  class="form-control" id="d_name" placeholder="Upload Document Name" required>
                    </div>
                    <div class="form-group">
                        <label for="course_id">Upload Document</label>
                        <input type="file" name="file"  class="form-control" id="file" required>
                    </div>
                </div>
                 <!-- /.card-body -->
                    <hr class="mt-1 mb-2">

                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-add-row">Upload</button>
                            <button class="btn btn-dark" type="reset">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
@else
<div class="card">
<div class="card-body">
    <h4>No Students</h4>
</div>
</div>
@endif
</div>

@endsection
