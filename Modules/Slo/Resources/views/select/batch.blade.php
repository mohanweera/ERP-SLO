<select class="form-control myDropdown" name="batch_id" id="batch_id">
<option value="">Select Batch</option>
@foreach($batches as $batches)
<option value="{{$batches->batch_id}}">{{$batches->batch_name}}</option>
@endforeach
</select>
<script>
$(function () {
    $("#batch_id").change(function(){
      var batch_id = $("#batch_id").val();
      $.ajax({
      type:'GET',
      url:'/getMiddleId',
      data:{batch_id:batch_id},
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        var dept_code = data.dept_code;
        var batchType_code = data.batchType_code;
        var batch_code = data.batch_code;
        $('#dep_code2').html(dept_code);
        $('#batch_type_code').html(batchType_code);
        $('#batch_code').html(batch_code);
        $("#std2").val(dept_code + batchType_code + batch_code);
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            Msg['danger'](item);
          });
        }
      });
  })
  });
</script>