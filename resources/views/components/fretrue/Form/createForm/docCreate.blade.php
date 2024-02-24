@extends('layout.main')

@section('title', 'สร้างฟอร์มขอเอกสารสำคัญ')




@section('contents')
    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="card-header  text-white bg-secondary">
            <h5 class="fs-5">ขอเอกสารสำคัญ</h5>
        </div>

        <form id="doc_create">

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
                            <label for="type_doc" class="form-label">ประเภทเอกสาร</label>
                            <select class="form-select" aria-label="Default select example" id="type_doc" name="type_doc">
                                <option value="" selected>เลือกประเภทเอกสาร</option>
                                <option value="ใบเสร็จเงินเดือน">ใบเสร็จเงินเดือน</option>
                                <option value="ใบรับรองการทำงาร">ใบรับรองการทำงาร</option>

                            </select>

                        </div>

                        <div class="col-6">
                            <label for="doc_lang" class="form-label">ภาษา</label>
                            <select class="form-select" aria-label="Default select example" id="doc_lang" name="doc_lang">
                                <option value="" selected>ภาษา</option>
                                <option value="ไทย">ไทย</option>
                                <option value="อังกฤษ">อังกฤษ</option>

                            </select>

                        </div>
                    </div>


                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-6">
                            <label for="rec_form" class="form-label">รูปแบบการรับ</label>
                            <select class="form-select" aria-label="Default select example" id="rec_form" name="rec_form">
                                <option value="" selected>เลือกรูปแบบการรับ</option>
                                <option value="รับด้วยตัวเอง">รับด้วยตัวเอง</option>
                                <option value="ส่งไปรษณี">ส่งไปรษณี ( กรอกที่อยู่ในรายละเอียด )</option>

                            </select>

                        </div>

                        <div class="col-6">
                            <label for="doc_amount" class="form-label">จำนวน</label>
                            <input type="number" class="form-control" id="doc_amount" placeholder="จำนวนกี่ฉบับ ...."
                                name="doc_amount">
                        </div>
                    </div>


                </div>

                <div class="mb-3">
                    <div class="row">

                        <div class="col-6">
                            <label for="doc_Y" class="form-label">ปี</label>                  
                            <input type="number" name="doc_Y" id="doc_Y" class="date-picker-year form-control"
                                placeholder="ปีไหน ...."  />
        
                        </div>

                        <div class="col-6">
                            <label for="doc_M" class="form-label">เดือน</label>

                            <input type="text" id="doc_M" placeholder="เดือนไหน ...." name="doc_M"
                                class="date-picker-month form-control">


                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" name="doc_desc" id="doc_desc"></textarea>
                                <label for="doc_desc">รายละเอียด</label>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="mb-3">
                    <div class="row">
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

            console.log({!! json_encode($mangerInfo) !!})
     
            $('#doc_M').datepicker({
                format: 'MM',
                viewMode: 'months',
                minViewMode: 'months'
            });

        });


        $('#doc_create').on('submit', (e) => {
            e.preventDefault()
            let form = $('#doc_create')[0];

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
                        url: "{{ route('docC.post') }}",
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

