<select class="form-control myDropdown" name="dept_id" id="dept_id" required>
<option value="">Select Department</option>
@foreach($dep as $dep)
<option value="{{$dep->dept_id}}">{{$dep->dept_name}}</option>
@endforeach
</select>
<script>
$(function () {
  
$("#dept_id").change(function(){
    var dept_id = $("#dept_id").val();
    $.ajax({
      type:'GET',
      url:'/getDepartmentCode',
      data:{dept_id:dept_id},
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        $("#dep_code").html(data.dept_code);
        $("#slqf_code").html(data.slqf);
        var dep_code = data.dept_code;
        var slqf = data.slqf;
        $('#std1').val(dep_code + slqf);
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            Msg['danger'](item);
          });
        }
      });
    
    if(dept_id !=''){
      $('#loadCourses').load('/select/courses/' + dept_id);
    }else{
      $('#loadCourses').load('/select/courses/' + 0);
    }
  })
});
</script>