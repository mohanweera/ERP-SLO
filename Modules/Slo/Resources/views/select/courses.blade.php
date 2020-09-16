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
    if(course_id !=''){
      $('#loadBatch').load('/select/batch/' + course_id);
    }else{
      $('#loadBatch').load('/select/batch/' + 0);
    }
  })
  });
</script>