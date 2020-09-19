@extends('slo::layouts.master')
@section('content')
@if(isset($cid))
<script>
    $(document).ready(function () {
        $("#course_id").val("{{$cid}}");
    });
</script>
@endif
<div class="container-fluid behind">
<form class="form-label-left input_mask needs-validation" method="POST" action="k" id="addFieldstoC" novalidate>
@csrf
    <input type="hidden" name="gId" id="gId" value="{{$group->GroupID ?? ''}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">

                            <h4 class="header-title">Manage Course Requirements</h4>

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
                <div class="col-md-7">
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
                
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="course">Types</label>
                        <select class="form-control " name="fields" id="fields" required>
                            <option value="1">Input Field</option>
                            <option value="2">Textarea</option>
                        </select>
                        <span id="fieldsadd" name="fieldsadd" class="btn btn-info">ADD</span>
                    </div>
                </div>
                </div>
                <div id="new_chq">
                @if($count !=0)
                @foreach($data as $data)
                <div class='row'>
                <div class='col-2'>
                <label for="course">{{$data->fname}}</label>
                </div>
                <div class='col-4'>
                @if($data->fid == 1)
                <input type='text'  class='form-control' disabled>
                @elseif($data->fid == 2)
                <textarea  class='form-control' disabled rows='4'></textarea>
                @endif
                </div>
                </div>
                <br/>
                @endforeach
                @endif
                </div>
                    <input type="hidden" value="0" id="total_des" name="total_des" class="form-control col-3">
                    <input type="hidden" value="0" id="total_chq" name="total_chq" class="form-control col-4">
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
    location.href = "/courseReq/" + course_id;
});
$("#fieldsadd").click(function(){
var fields = $("#fields").val();
if(fields == 1){
    var new_chq_no = parseInt($('#total_chq').val()) + 1;
    var new_des_no = parseInt($('#total_des').val()) + 1;
    var fields2 = "<input type='hidden' id='rowid' value='" + new_des_no + "'><input type='text' name='fieldType_"+new_des_no+"' style='display:none' value='" + fields +"'>";
    var new_input = "<div class='col-3'><input type='text' name='new_" + new_des_no + "' id='new_" + new_des_no + "' class='form-control' placeholder='Name'></div>";
    var new_input2 = "<div class='col-4'><input type='text' name='new_" + new_chq_no + "' id='new_" + new_chq_no + "' class='form-control' disabled></div><span class='btn btn-danger col-1' id='delBut'>Del</span>";
    $('#new_chq').append("<abc id='rr_"+ new_des_no +"'>" + fields2 + "<div class='row'>" + new_input + new_input2 + "</div></abc><br/>");
    $('#total_chq').val(new_chq_no);
    $('#total_des').val(new_chq_no);
}
if(fields == 2){
    var new_chq_no = parseInt($('#total_chq').val()) + 1;
    var new_des_no = parseInt($('#total_des').val()) + 1;
    var fields2 = "<input type='hidden' id='rowid' value='" + new_des_no + "'><input type='text' name='fieldType_"+new_des_no+"'  style='display:none' value='" + fields +"'>";
    var new_input = "<div class='col-3'><input type='text' name='new_" + new_des_no + "' id='new_" + new_des_no + "' class='form-control' placeholder='Name'></div>";
    var new_input2 = "<div class='col-4'><textarea name='new_" + new_chq_no + "' id='new_" + new_chq_no + "' class='form-control' disabled rows='4'></textarea></div><span class='btn btn-danger col-1' id='delBut'>Del</span>";
    $('#new_chq').append("<abc id='rr_"+ new_des_no +"'>" + fields2 + "<div class='row'>" + new_input + new_input2 + "</div></abc><br/>");
    $('#total_chq').val(new_chq_no);
    $('#total_des').val(new_chq_no);
}

});

});

$(document).on('click', '#delBut', function(){
    var row = $(this).closest("abc"),       // Finds the closest row <tr> 
                rid = row.find("#rowid").val();
                $("#rr_" + rid).hide();
                $("#new_" + rid).val('');         
                
});
</script>
@endsection
