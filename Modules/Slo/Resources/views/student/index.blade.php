@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<div class="card">
<div class="card-header">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="header-title">Student List</h4>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
            <a href="/addNewUpCat" class="btn btn-info"><span class="fa fa-plus"></span> Add New</a>
            <a href="/upCatTrashList" class="btn btn-info"><span class="fa fa-trash"></span> View Trash</a>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <table id="data-table" class="table table-bordered table-striped">
    <thead class="thead-dark">
    <tr>
    <th>Student Admission Number</th>
    <th>Student Name</th>
	<th>Action</th>
    </tr>
    </thead>
    <tbody>
    
    <tr>
    <td></td>
    <td></td>
    <td>
    <input type="hidden" class="id" id="id" value="">
    <a href="/stdUpdate/10"><div class="btn btn-xs"><span class="fa fa-edit"></span> Edit</div></a>
    <div class="btn btn-xs trashBut" ><span class="fa fa-trash"></span> Trash</div></div>
    </td>
    </tr>
    </tbody>
    </table>
</div>
</div>
</div>
<script>
    $(document).ready(function () {
        $(".trashBut").click(function() {
            var row = $(this).closest("tr"),       // Finds the closest row <tr> 
                tds = row.find("td");
                batch_id = tds.find(".id").val();  
                
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to trash this?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                    type:'GET',
                    url:'/trashBatch',
                    data:{batch_id:batch_id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success:function(data){
                        if(data.msg == 1){
                        toastr.success('Trashed Successfully');
                        location.reload();
                        }else{
                        toastr.error('Trashing Error');
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
