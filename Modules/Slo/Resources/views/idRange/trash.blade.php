@extends('slo::layouts.master')
@section('content')
<div class="container-fluid behind">
<div class="card">
<div class="card-header">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="header-title">KIU ID Range Trashed List</h4>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
            <a href="/addNewIdRange" class="btn btn-info"><span class="fa fa-plus"></span> Add New</a>
            <a href="/idRangeList" class="btn btn-info"><span class="fa fa-list"></span> View List</a>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
    <thead class="thead-dark">
    <tr>
    <th>Course</th>
    <th>Start Number</th>
    <th>End Number</th>
    <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $results)
    <tr>
    <td>{{$results->course_name}}</td>
    <td>{{$results->start}}</td>
    <td>{{$results->end}}</td>
    <td class="row">
    <div class="col-sm">
    <input type="hidden" class="id" id="id" value="{{$results->id}}">
    @if($results->cgsid != "")
    <span class="badge badge-success">Active</span>
    @endif 
    </div>
    <div class="col-sm">
    @if($results->cgsid == "" && $results->hold == 0)
        <a href="/addNewIdRange/{{$results->id}}"><div class="btn btn-xs"><span class="fa fa-edit"></span> Edit</div></a>
    @endif
    </div>
    <div class="col-sm">
    <div class="btn btn-xs trashBut" ><span class="fa fa-window-restore"></span> Restore</div></div>
    </div>
    <div class="col-sm">
    @if($results->hold == 0)
    <div class="btn btn-xs holdhBut" ><span class="fa fa-pause"></span> Hold</div></div>
    @else
    <span class="badge badge-warning"><span class="fa fa-check"></span> Hold</span>
    @endif
    </div>
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
        $(".trashBut").click(function() {
            var row = $(this).closest("tr"),       // Finds the closest row <tr> 
                tds = row.find("td");
                idRange_id = tds.find(".id").val();  
                
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
                    url:'/trashIdRange',
                    data:{idRange_id:idRange_id},
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
        $('#example1').DataTable({
            dom: 'lBfrtip',

            buttons: [
                { extend: 'copy', 'className': 'button' },
                { extend: 'csv', className: 'button' },
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
