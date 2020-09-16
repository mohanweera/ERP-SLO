///////////////////////////////////////////////////
//////////// Supun Widanapathirana/////////////////
///////////////////////////////////////////////////

$(document).ready(function(){
  ///////////////// Add Batch Types /////
  $("#addNewBatchTypes").submit(function(event){
    event.preventDefault(); //prevent default action 
    var form_data = new FormData(this); //Creates new FormData object
    $.ajax({
      type:'POST',
      url:'/addNewBatchTypes',
      data : form_data,
      contentType: false,
      cache: false,
      processData:false,
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        if(data.msg == 1){
          toastr.success('Batch Type Added Successfully');
          $('#addNewBatchTypes').trigger("reset");
        }else{
          toastr.error('Batch Type Adding Error');
        }
        
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            toastr.danger('Something error');
          });
        }
      });
  });
  ///////////////// Add Batches /////
  $("#batchAddForm").submit(function(event){
    event.preventDefault(); //prevent default action 
    var form_data = new FormData(this); //Creates new FormData object
    $.ajax({
      type:'POST',
      url:'/batchAddForm',
      data : form_data,
      contentType: false,
      cache: false,
      processData:false,
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        if(data.msg == 1){
          toastr.success('Batch Added Successfully');
          $('#batchAddForm').trigger("reset");
        }else{
          toastr.error('Batch Adding Error');
        }
        
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            toastr.danger('Something error');
          });
        }
      });
  });

  ///////////////// Add New Studendt /////
  $("#studentAddForm").submit(function(event){
    event.preventDefault(); //prevent default action 
    var form_data = new FormData(this); //Creates new FormData object
    $.ajax({
      type:'POST',
      url:'/addNewStudent',
      data : form_data,
      contentType: false,
      cache: false,
      processData:false,
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        if(data.msg == 1){
          toastr.success('ID Range Added Successfully');
          $('#studentAddForm').trigger("reset");
        }else{
          toastr.error('ID Range Adding Error');
        }
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            toastr.danger('Something error');
          });
        }
      });
  });
  ///////////////// Add ID Range /////
  $("#addNewIdRange").submit(function(event){
    event.preventDefault(); //prevent default action 
    var form_data = new FormData(this); //Creates new FormData object
    $.ajax({
      type:'POST',
      url:'/idRangeAddForm',
      data : form_data,
      contentType: false,
      cache: false,
      processData:false,
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        if(data.msg == 1){
          toastr.success('ID Range Added Successfully');
          $('#addNewIdRange').trigger("reset");
          $("#start").val(data.start);
        }else{
          toastr.error('ID Range Adding Error');
        }
        
      },
      error: function(xhr, status, error) 
        {
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            toastr.danger('Something error');
          });
        }
      });
  });
  $("#faculty_id").change(function(){
        var faculty_id = $("#faculty_id").val();
        if(faculty_id !=''){
          $('#loadDep').load('/select/departments/' + faculty_id);
        }else{
          $('#loadDep').load('/select/departments/' + 0);
        }
  })
  
  
  $("#batch_type").change(function(){
    var batch_type = $("#batch_type").val();
    if(batch_type !=""){
      $("#batchCode").show();
    }else{
      $("#batchCode").hide();
    }
    $.ajax({
      type:'GET',
      url:'/loadBatchCode',
      data:{batch_type:batch_type},
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success:function(data){
        $("#batch_code").val(data.batch_code);
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
