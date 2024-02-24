@extends('layout.main')

@section('title', 'Doc Page')




@section('contents')
    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="card-header  text-white bg-secondary">
            <h5 class="fs-5">ขอลา</h5>
        </div>

        <form id="leave_create">
            @csrf
            <div class="card-body">
                <div class="card-title mb-5 mt-3">
                    <a href="/" class="btn btn-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <i class='bx bx-left-arrow-alt'></i>
                            ย้อนกลับ
                        </div>
                    </a>

                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-6">
                            <label for="type_leave" class="form-label">ประเภทการลา</label>
                            <select class="form-select" aria-label="Default select example" id="type_leave"
                                name="type_leave">
                                <option value="" selected>เลือกประเภทการลา</option>
                                <option value="ลาป่วย">ลาป่วย</option>
                                <option value="ลากิจ">ลากิจ</option>

                            </select>

                        </div>

                        <div class="col-3">
                            <label for="s_date" class="form-label">วันเริ่มต้นลา</label>
                            <input id="s_date" name="s_date" class="form-control" placeholder="เลือกวันที่ลา" />
                        </div>


                        <div class="col-3">
                            <label for="e_date" class="form-label">วันลาสุดท้าย</label>
                            <input id="e_date" name="e_date" class="form-control" placeholder="เลือกวันที่ลา" />
                        </div>
                    </div>


                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" name="leave_desc" id="leave_desc"></textarea>
                                <label for="leave_desc">รายละเอียด</label>
                            </div>
                        </div>

                    </div>

                </div>



                <div class="mb-3">
                    <div class="row">

                        <div class="col-6">
                            <label for="leave_file" class="form-label">เอกสารแนบ</label>
                            <input type="file" name="leave_file" class="form-control" id="leave_file">
                        </div>

                        <div class="col-6">
                            <label for="to_menager" class="form-label">ถึงใคร</label>
                            <select class="form-select" aria-label="Default select example" id="to_menager"
                                name="to_menager">
                                <option value="" selected>เลือกหัวหน้า</option>
                                @foreach ($mangerInfo as $items)
                                    <option value="{{ $items->id }}">{{ $items->name }}</option>
                                @endforeach


                            </select>
                        </div>

                    </div>

                </div>


                <div class="mt-5 mb-3 d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-success">Create</button>

                </div>


            </div>
        </form>


    </div>

    <script>
        $(document).ready(function() {

            $('#s_date').datepicker({
                uiLibrary: 'bootstrap5',
                format: 'yyyy-mm-dd',
                autoclose: true,
                select: function(e, type) {
                    $('#e_date').datepicker('setMinDate', e.date.addDays(1));
                }
            });

            $('#e_date').datepicker({
                uiLibrary: 'bootstrap5',
                format: 'yyyy-mm-dd',
                autoclose: true,
                select: function(e, type) {
                    $('#s_date').datepicker('setMaxDate', e.date.addDays(-1));
                }
            });
        });

        // $(function() {
        //     $('#datetimepicker6').datetimepicker();
        //     $('#datetimepicker7').datetimepicker({
        //         useCurrent: false
        //     });
        //     $("#datetimepicker6").on("dp.change", function(e) {
        //         $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        //     });
        //     $("#datetimepicker7").on("dp.change", function(e) {
        //         $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        //     });
        // });




        $('#leave_create').on('submit', (e) => {
            e.preventDefault()
            let form = $('#leave_create')[0];

            let data = new FormData(form)

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create this form ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Create it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leaveC.post') }}",
                        type: "POST",
                        data: data,
                        dataType: "JSON",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false,
                        contentType: false,

                        success: function(response) {

                            if (response.error) {
                                var errorMsg = ''

                                if (typeof response.error === 'object') {

                                    $.each(response.error, function(index, error) {
                                        errorMsg += error + "<br>";
                                    });

                                } else {
                                    errorMsg += response.error + "<br>";
                                }



                                iziToast.error({
                                    title: 'Hey',
                                    message: errorMsg,
                                    position: 'topCenter'
                                });
                            } else {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Your Form has been Created.",
                                    icon: "success"
                                }).then(() => {
                                    console.log(response.data)
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


        })
    </script>
@endsection
