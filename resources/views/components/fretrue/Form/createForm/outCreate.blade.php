@extends('layout.main')

@section('title', 'Doc Page')




@section('contents')
    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="card-header  text-white bg-secondary">
            <h5 class="fs-5">ขอทำงานนอกสถานที่</h5>
        </div>

        <form id="outCreate">
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
                            <label for="s_date" class="form-label">วันเริ่มต้น</label>
                            <input class="form-control" id="s_date" name="s_date" placeholder="เลือกวันเริ่มต้นที่ทำงาน" />
                        </div>


                        <div class="col-6">
                            <label for="e_date" class="form-label">วันสุดท้าย</label>
                            <input class="form-control" id="e_date" name="e_date" placeholder="เลือกวันสุดท้ายที่ทำงาน" />
                        </div>
                    </div>


                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-6">

                            <label for="out_place" class="form-label">ชื่อสถานที่</label>
                            <input type="text" class="form-control" id="out_place" placeholder="ชื่อสถานที่ ...."
                                name="out_place">

                        </div>


                        <div class="col-6">
                            <label for="out_direc" class="form-label">ที่อยู่</label>
                            <div class="form-floating">
                                <textarea rows="5" class="form-control h-25" placeholder="Leave a comment here" name="out_direc" id="out_direc"></textarea>
                                <label for="out_direc">ที่อยู่</label>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" name="out_desc" id="out_desc"></textarea>
                                <label for="out_desc">รายละเอียด</label>
                            </div>
                        </div>

                    </div>

                </div>


                
                <div class="mb-3">
                    <div class="row">

                        <div class="col-6">
                            <label for="out_file" class="form-label">เอกสารแนบ</label>
                            <input type="file" name="out_file" class="form-control" id="out_file">
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
                autoclose: true
            });

            $('#e_date').datepicker({
                uiLibrary: 'bootstrap5',
                format: 'yyyy-mm-dd',
                autoclose: true
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




        $('#outCreate').on('submit', (e) => {
            e.preventDefault()
            let form = $('#outCreate')[0];

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
                        url: "{{ route('outC.post') }}",
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
