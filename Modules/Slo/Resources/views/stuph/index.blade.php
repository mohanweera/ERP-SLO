@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<div class="card">
<div class="card-header">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="header-title">KIU Students Upload</h4>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
            
            </div>
        </div>
    </div>
</div>
<form action="/searchUploads" method="GET" novalidate class="form-label-left input_mask needs-validation">
<div class="card-body row">
<div class="input-group col-md-6">
<input type="number" class="form-control rounded-0" name="stId" id="stId" required>
<span class="input-group-append">
    <button type="submit" class="btn btn-info btn-flat">Search By ID</button>
</span>
</div>
</div>
</form>
</div>
</div>    

@endsection
