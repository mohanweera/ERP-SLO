@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<div class="card">
<div class="card-header">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="header-title">KIU Batch Trashed List</h4>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
            <a href="/addNewBatch" class="btn btn-info"><span class="fa fa-plus"></span> Add New</a>
            <a href="/batchList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    
    <table id="data-table" class="table table-bordered table-striped">
    <thead class="thead-dark">
    <tr>
    <th>BATCH CODE</th>
    <th>COURSE</th>
    <th>BATCH</th>
	<th>ACTION</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $results)
    <tr>
    <td>{{$results->batch_code}}</td>
    <td>{{$results->course_name}}</td>
	<td>{{$results->batch_name}}</td>
    <td>
    <input type="hidden" class="id" id="id" value="{{$results->batch_id}}">
    <a href="/addNewBatch/{{$results->batch_id}}"><div class="btn btn-xs"><span class="fa fa-edit"></span> Edit</div></a>
    <div class="btn btn-xs trashBut" ><span class="fa fa-window-restore"></span> Restore</div></div>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
</div>
</div>
</div>
<script>
    $(document).ready(function () {
        //alert();
        $(".trashBut").click(function() {
            var row = $(this).closest("tr"),       // Finds the closest row <tr> 
                tds = row.find("td");
                batch_id = tds.find(".id").val();  
                
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to restore this?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                    type:'GET',
                    url:'/trashBatch',
                    data:{batch_id:batch_id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success:function(data){
                        if(data.msg == 1){
                        toastr.success('Restored Successfully');
                        location.reload();
                        }else{
                        toastr.error('Restoring Error');
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
                }
            })
        });
        $('#data-table').DataTable({
            dom: 'lBfrtip',

            buttons: [
                { extend: 'copy', 'className': 'button' },
                { extend: 'csv', className: 'button ' },
                { extend: 'excel', className: 'button ' },
                { extend: 'pdf', className: 'button' },
                { extend: 'print', className: 'button' }

            ],
            "oLanguage": {
                "sLengthMenu": "Show _MENU_", // **dont remove _MENU_ keyword**
            },
            "responsive": true,
            "paging": true,
            "searching": true,
            "ordering": true,

            "columnDefs": [{
                "targets": 2,

                "orderTable": false
            }],


        });

    });
</script>

@endsection
