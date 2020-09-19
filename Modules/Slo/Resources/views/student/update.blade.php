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
                                <label for="batch_type">Faculty</label>
                                <select class="form-control" disabled>
                                <option>{{$Student->faculty_name}}</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_type">Department</label>
                                <select class="form-control" disabled>
                                <option>{{$Student->dept_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_type">Course</label>
                                <select class="form-control" disabled>
                                <option>{{$Student->course_name}}</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_type">Batch</label>
                                <select class="form-control" disabled>
                                <option>{{$Student->batch_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-3">
                            <label for="batch_type">Title</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                    <select class="form-control col-lg-12 " required="required" name="std_title" id="std_title" style="width:144px">
                                        <option>Select Title</option>
                                        <option value="Mr">Mr.</option>
                                        <option value="Ms">Ms.</option>
                                        <option value="Mrs">Mrs.</option>
                                        <option value="Rev">Rev.</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3" align="left">
                            <label for="batch_type">Gender</label>
                                <div id="genderContainer">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="gender" value="male"> Male
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="gender" value="female"> Female
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="gender" value="other"> Other
                                        </label>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <label for="batch_type">Full Name</label>
                                <div class=" input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <textarea class="form-control " name="full_name" placeholder="Full Name" required="required" rows="1" cols="50"></textarea>
                                </div>
                                <label for="batch_type">NIC / Passport</label>
                                <div class=" input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                    </div>
                                    <input type="text" class="form-control nic-pass " id="nicpass" name="nicpass" placeholder="NIC/Passport" pattern="[0-9]{9}[x|X|v|V]|[0-9]{12}" required="required">
                                </div>
                                
                                <label for="batch_type">Register Date</label>
                                <div class=" input-group md-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" class="form-control " placeholder="Initial Starting Date" name="reg_date">
                                </div>
                                <br>
                            </div>

                            <div class="col-md-6">
                            <label for="batch_type">Name with Initials</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control " name="name_initials" placeholder="Name with Initials" required="required">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>
                                <label for="batch_type">Mobile No</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control mobile " name="tel_mobile1" placeholder="Mobile No eg:0711234567" pattern="[0]{1}[0-9]{9}" required="required">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
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
