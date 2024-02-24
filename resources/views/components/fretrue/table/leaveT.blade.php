<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>


<div class="container">
    <table class="table table-bordered" id="leaveTable">
        <thead>
            <tr class="text-center table-Info">
                <th scope="col">id</th>
                <th scope="col">ประเภทการลา</th>
                <th scope="col">ชื่อผู้ขอ</th>
                <th scope="col">สถานะฟอร์ม</th>

                <th scope="col">action</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $items)
                <tr
                    class="text-center {{ $items->status_form === 'Wait Progress' ? '' : ($items->status_form === 'Reject' ? 'table-danger' : 'table-success') }}">
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>{{ $items->absence_type }}</td>
                    <td>{{ $items->name }}</td>
                    <td>
                        @if ($items->status_form === 'Wait Progress')
                            <a class="btn btn-info">{{ $items->status_form }}</a>
                        @elseif($items->status_form === 'Reject')
                            <a class="btn btn-danger">{{ $items->status_form }}</a>
                        @else
                            <a class="btn btn-success">{{ $items->status_form }}</a>
                        @endif

                    </td>
                    <td>
                        <a href="/leaveForm/{{ $items->absence_id }}" class="btn btn-success"><i
                                class='bx bx-detail'></i></a>
                        @if ($items->status_form === 'Wait Progress' && auth()->user()->department !== 'Manager')
                            <a href="/leaveEdit/{{ $items->absence_id }}" class="btn btn-primary"><i
                                    class='bx bx-edit-alt' style='color:#ffffff'></i></a>
                            <a href="#" class="btn btn-danger delete-doc"
                                data-leave-id="{{ $items->absence_id }}"
                                data-leave-img="{{ $items->absence_path }}"><i class='bx bxs-trash'
                                    style='color:#ffffff'></i></a>
                        @endif

                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

</div>



<script>
    $(document).ready(function() {
        console.log("Table Leave Data => ", {!! json_encode($data) !!})
        $('#leaveTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ]
            // responsive: true
        });


        $('.delete-doc').on('click', function(e) {
            e.preventDefault();
            var Id = $(this).data('leave-id');
            var Img = $(this).data('leave-img');
            deleteDoc(Id, Img);
        });
    });


    function deleteDoc(id, Img) {
        console.log("Form ที่ Delete ", id)
        console.log("รูป ที่ Delete ", Img)

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to Delete form ขอลา id : " + id,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Create it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/leaveDel/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,

                    success: function(response) {

                        if (response.error) {

                            iziToast.error({
                                title: 'Hey',
                                message: response.error,
                                position: 'topCenter'
                            });
                        } else {
                            Swal.fire({
                                title: "Success!",
                                text: response.success,
                                icon: "success"
                            }).then(() => {
                                console.log(response.msg)
                                window.location.href = '/';
                            });

                        }

                    },
                    error: function(xhr, status, error) {
                        iziToast.error({

                            message: error,
                            position: 'topCenter'
                        });
                    }


                });

            }
        });



    }
</script>
