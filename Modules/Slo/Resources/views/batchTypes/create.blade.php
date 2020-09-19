@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<form class="form-label-left input_mask needs-validation" method="post" action="" id="addNewBatchTypes" novalidate>
    @csrf
    <input type="hidden" name="tId" id="tId" value="{{$batchData->id ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title">Add New Batch Type</h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            <a href="/batchTypeList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
                            <a href="/batchTypeTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_type">Batch Type Code</label>
                                @if(isset($batchData))
                                <input type="number" class="form-control" name="batch_type" id="batch_type" value="{{$batchData->batch_type ?? ''}}" readonly required>
                                @else
                                <input type="number" class="form-control" name="batch_type" id="batch_type"
                                       placeholder="00" pattern="[0-9]{2}" title="two number code" value="" required>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" required>{{$batchData->description ?? ''}}</textarea>
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
    </div>
</form>
</div>

@endsection
