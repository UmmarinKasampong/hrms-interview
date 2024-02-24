@extends('layout.main')

@section('title', 'แก้ไขฟอร์มขอทำงานนอกสถานที่')




@section('contents')
    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="card-header  text-white bg-warning">
            <h5 class="fs-5">ขอทำงานนอกสถานที่</h5>
        </div>


        <form id="out_Edit">
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
                            <input class="form-control" id="s_date" name="s_date" value="{{ $data[0]->offsite_start }}"
                                placeholder="เลือกวันเริ่มต้นที่ทำงาน" />
                        </div>


                        <div class="col-6">
                            <label for="e_date" class="form-label">วันสุดท้าย</label>
                            <input class="form-control" id="e_date" name="e_date" value="{{ $data[0]->offsite_end }}"
                                placeholder="เลือกวันสุดท้ายที่ทำงาน" />
                        </div>
                    </div>


                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-6">

                            <label for="out_place" class="form-label">ชื่อสถานที่</label>
                            <input type="text" class="form-control" id="out_place" placeholder="ชื่อสถานที่ ...."
                                name="out_place" value="{{ $data[0]->offsite_place }}">

                        </div>


                        <div class="col-6">
                            <label for="out_direc" class="form-label">ที่อยู่</label>
                            <div class="form-floating">
                                <textarea rows="5" class="form-control h-25" placeholder="Leave a comment here" name="out_direc" id="out_direc">{{ $data[0]->offsite_direc }}</textarea>
                                <label for="out_direc">ที่อยู่</label>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" name="out_desc" id="out_desc">{{ $data[0]->offsite_desc }}</textarea>
                                <label for="out_desc">รายละเอียด</label>
                            </div>
                        </div>

                    </div>

                </div>



                <div class="mb-3">
                    <div class="row">

                        
                        <div class="col-6">
                            <label for="leave_file" class="form-label">เอกสารแนบ</label>
                            @if ($data[0]->offsite_path)
                                <div class="d-flex" id="file_show">
                                    <a id="file_d" href="{{ url('storage/uploads/outDoc/' . $data[0]->offsite_path) }}"
                                        class="btn btn-success" download><i class='bx bx-file'
                                            style='color:#ffffff'></i></a>
                                    <a class="btn btn-danger ms-2 " id="del_file">delete file</a>
                                </div>
                                <input type="file" name="out_file" class="form-control" id="out_file">
                            @else
                                <input type="file" name="out_file" class="form-control" id="non_file">
                            @endif

                        </div>

                    

                        <div class="col-6">
                            <label for="to_menager" class="form-label">ถึงใคร</label>
                            <select class="form-select" aria-label="Default select example" id="to_menager"
                                name="to_menager">
                                <option value="" selected>เลือกหัวหน้า</option>
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
            $('#out_file').hide()
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


        $('#del_file').on('click', (e) => {
            e.preventDefault()
            del_btn = !del_btn;
            $('#file_d').hide()
            $('#del_file').hide()
            $('#out_file').show()
        })



        $('#out_Edit').on('submit', (e) => {
            e.preventDefault()
           

            let form = $('#out_Edit')[0];

            let data = new FormData(form)
            data.append('form_id',  {!! json_encode($data[0]->offsite_id) !!});
            data.append('old_file_path',  {!! json_encode($data[0]->offsite_path) !!});
            if(del_btn){
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
                        url: "{{ route('outE.post') }}",
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
