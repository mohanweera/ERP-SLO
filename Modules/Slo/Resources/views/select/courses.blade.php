<select class="form-control myDropdown" name="course_id" id="course_id">
    <option value="">Select Course</option>
    @foreach($courses as $courses)
    <option value="{{$courses->course_id}}">{{$courses->course_name}}</option>
    @endforeach
</select>
<script>
$(function () {
    $("#course_id").change(function(){
      var course_id = $("#course_id").val();
      $.ajax({
      type:'GET',
      url:'/getStdSeriel',
      data:{course_id:course_id},
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        if(data.last_id > 0){
          var serial = data.last_id;
          $('#std_id').html(serial);
          $('#std3').val(serial);
          $('#idrange').val(data.idrange);
        }else{
          $('#std_id').html("00000");
          $('#std3').val(0);
          $('#idrange').val('');
        }
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            Msg['danger'](item);
          });
        }
      });
    
    if(course_id !=''){
      $('#loadBatch').load('/select/batch/' + course_id);
    }else{
      $('#loadBatch').load('/select/batch/' + 0);
    }
  })
  });
</script>