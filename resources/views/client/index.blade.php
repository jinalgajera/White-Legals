@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Clients</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('home') }}"> Back to Dashboard</a>
                @can('client-create')
                <a class="btn btn-success" href="{{ route('clients.create') }}"> Create New Client</a>
                @endcan
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered client-table">
        <thead>
            <tr> 
                <th>Employee Name</th>         
                <th>Client Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Notes</th>
                <th width="280px">Action</th>
            </tr>    
        </thead>
        <tbody></tbody>    
    </table>
@endsection
@section('script')
<script>
$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('.client-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{url('getClientsData') }}",
        columns: [
            { data: 'empname.name'},
            { data: 'name'},
            { data: 'email'},           
            { data: 'address'},
            { data: 'notes'},
            { data: 'action',orderable: false, searchable: false},
        ],   
    });

    /**delete user */
    $(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault();
        var linkURL = $(this).attr("href");
        var id = $(this).data("id");
        Swal.fire({
            title: 'Are you sure you want to Delete?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            confirmButtonClass: 'btn btn-warning',
            cancelButtonClass: 'btn btn-outline-danger ml-1',
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    url: "{{url('clients/destroy')}}"+'/'+id,
                    type: 'DELETE',
                    dataType:'json',
                    data: {id:id},
                    success: function(res){
                        if(res == true){
                            swal.fire({
                                type: "success",
                                title: 'Deleted!',
                                text: 'Client deleted successfully.',
                                confirmButtonClass: 'btn btn-success',
                            })
                            setTimeout(function(){
                                location.reload();
                            }, 1000);
                        } else {
                            swal.fire({
                                type: "error",
                                title: 'Error!',
                                text: 'Some Problem Occured...Please Try Again',
                                confirmButtonClass: 'btn btn-success',
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
@endsection