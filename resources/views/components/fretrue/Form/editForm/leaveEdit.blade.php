@extends('layout.main')

@section('title', 'แก้ไขฟอร์มขอลา')




@section('contents')
    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="card-header  text-white bg-warning">
            <h5 class="fs-5">ขอลา</h5>
        </div>

        <form id="leave_Edit">
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
                                <option value="" {{ empty($data[0]->absence_type) ? 'selected' : '' }}>
                                    เลือกประเภทการลา</option>
                                <option value="ลาป่วย" {{ $data[0]->absence_type === 'ลาป่วย' ? 'selected' : '' }}>ลาป่วย
                                </option>
                                <option value="ลากิจ" {{ $data[0]->absence_type === 'ลากิจ' ? 'selected' : '' }}>ลากิจ
                                </option>

                            </select>

                        </div>

                        <div class="col-3">
                            <label for="s_date" class="form-label">วันเริ่มต้นลา</label>
                            <input class="form-control" id="s_date" name="s_date" placeholder="เลือกวันที่ลา"
                                value="{{ $data[0]->absence_start }}" />
                        </div>


                        <div class="col-3">
                            <label for="e_date" class="form-label">วันลาสุดท้าย</label>
                            <input class="form-control" id="e_date" name="e_date" placeholder="เลือกวันที่ลา"
                                value="{{ $data[0]->absence_end }}" />
                        </div>
                    </div>


                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" name="leave_desc" id="leave_desc">{{ $data[0]->absence_desc }}</textarea>
                                <label for="leave_desc">รายละเอียด</label>
                            </div>
                        </div>

                    </div>

                </div>



                <div class="mb-3">
                    <div class="row">

                        <div class="col-6">
                            <label for="leave_file" class="form-label">เอกสารแนบ</label>
                            @if ($data[0]->absence_path)
                                <div class="d-flex" id="file_show">
                                    <a id="file_d" href="{{ url('storage/uploads/leaveDoc/' . $data[0]->absence_path) }}"
                                        class="btn btn-success" download><i class='bx bx-file'
                                            style='color:#ffffff'></i></a>
                                    <a class="btn btn-danger ms-2 " id="del_file">delete file</a>
                                </div>
                                <input type="file" name="leave_file" class="form-control" id="leave_file">
                            @else
                                <input type="file" name="leave_file" class="form-control" id="non_file">
                            @endif

                        </div>

                        <div class="col-6">
                            <label for="to_menager" class="form-label">ถึงใคร</label>
                            <select class="form-select" aria-label="Default select example" id="to_menager"
                                name="to_menager">
                                <option value="" {{ empty($data[0]->to_manager) ? 'selected' : '' }}>เลือกหัวหน้า
                                </option>
                                @foreach ($mangerInfo as $items)
                                    <option value="{{ $items->id }}"
                                        {{ $data[0]->to_manager === $items->id ? 'selected' : '' }}>{{ $items->name }}
                                    </option>
                                @endforeach


                            </select>
                        </div>

                    </div>

                </div>


                <div class="mt-5 mb-5 d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-primary">Edit Form</button>
                </div>



            </div>
        </form>

    </div>

    <script>
        let del_btn = false;
        $(document).ready(function() {

            console.log("Form doc Data => ", {!! json_encode($data) !!})
            $('#leave_file').hide()

            $('#s_date').datepicker({
                uiLibrary: 'bootstrap5',
                format: 'yyyy-mm-dd',
                autoclose: true,

            });

            $('#e_date').datepicker({
                uiLibrary: 'bootstrap5',
                format: 'yyyy-mm-dd',
                autoclose: true,

            });
        });

        $('#del_file').on('click', (e) => {
            e.preventDefault()
            del_btn = !del_btn;
            $('#file_d').hide()
            $('#del_file').hide()
            $('#leave_file').show()
        })



        $('#leave_Edit').on('submit', (e) => {
            e.preventDefault()


            let form = $('#leave_Edit')[0];

            let data = new FormData(form)
            data.append('form_id', {!! json_encode($data[0]->absence_id) !!});
            data.append('old_file_path', {!! json_encode($data[0]->absence_path) !!});
            if (del_btn) {
                data.append('del_file', del_btn);
                console.log("ลบไฟล์หรือเปล่า => ", del_btn)
            }

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
                        url: "{{ route('leaveE.post') }}",
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
                                    text: response.success,
                                    icon: "success"
                                }).then(() => {
                                    console.log(response.msg)
                                    console.log(response.all)
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
    </script>
@endsection
