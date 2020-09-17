@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<form class="form-label-left input_mask needs-validation" method="post" action="" id="uploadCatForm" novalidate>
@csrf
<input type="hidden" name="ucId" id="ucId" value="{{$data->upload_cat_id  ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="header-title">Add New Upload Category</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            <a href="/uploadCtList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
                            <a href="/upCatTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_id">Category Name</label>
                                <input type="text" class="form-control" name="c_name" id="c_name" placeholder="Category Name" required value="{{$data->category_name ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="batch_type">Category Code</label>
                                <input type="text" class="form-control" name="c_code" id="c_code" placeholder="Category Code" required value="{{$data->cat_code ?? ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="batch_start_date">Description</label>
                        <textarea class="form-control" name="description" id="description" required placeholder="Category Description">{{$data->description ?? ''}}</textarea>
                    </div>
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

</form>
</div>

@endsection
