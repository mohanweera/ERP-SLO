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

                            <h4 class="header-title myTitle">Main Section </h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            
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

                    
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title myTitle" data-toggle="collapse" data-target="#gc">Genaral Section <span class="fa fa-plus"></span></h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body collapse" id="gc">
                @foreach($genaral as $genaral)
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="type">{{$genaral->fname}}</label>
                                @if($genaral->fid == 1)
                                <input type="text" class="form-control" name="{{$genaral->inputname}}" id="{{$genaral->inputname}}" value="" required>
                                @elseif($genaral->fid == 2)
                                <textarea class="form-control" name="{{$genaral->inputname}}" id="{{$genaral->inputname}}" required></textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach 
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title myTitle" data-toggle="collapse" data-target="#sc">Special Requirements Section <span class="fa fa-plus"></span></h4>

                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="card-body collapse" id="sc">
                @foreach($special as $special)
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="type">{{$special->fname}}</label>
                                @if($special->fid == 1)
                                <input type="text" class="form-control" name="{{$special->inputname}}" id="{{$special->inputname}}" value="" required>
                                @elseif($special->fid == 2)
                                <textarea class="form-control" name="{{$special->inputname}}" id="{{$special->inputname}}" required></textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach 
                </div>
            </div>
            <div class="card">
                <div class="card-body">
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
