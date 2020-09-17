@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<form class="form-label-left input_mask needs-validation" method="post" action="" id="addNewHospital" novalidate>
    @csrf
    <input type="hidden" name="hId" id="hId" value="{{$data->gen_hospital_id  ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title">Add New Hospital</h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            <a href="/hospitalsList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
                            <a href="/hospitalsTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_type">Hospital Name</label>
                                <input type="text" class="form-control" name="h_name" id="h_name" value="{{$data->hospital_name ?? ''}}" required>
                            </div>
                        </div>
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
    </div>
</form>
</div>

@endsection
