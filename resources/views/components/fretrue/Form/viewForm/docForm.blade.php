@extends('layout.main')

@section('title', 'ฟอร์มขอเอกสารสำคัญ')




@section('contents')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="fs-5 d-flex justify-content-between align-items-center card-header  text-white {{ $data[0]->status_form === 'Wait Progress' ? 'bg-info' : ($data[0]->status_form === 'Reject' ? 'bg-danger' : 'bg-success') }}">
            ขอเอกสารสำคัญ  <a class="btn btn-primary">{{ $data[0]->status_form}}</a>

        </div>


        <form id="doc_View">
            <div class="card-body">

                <div class="card-title mb-5 mt-3">
                    <a href="/" class="btn btn-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <i class='bx bx-left-arrow-alt'></i>
                            ย้อนกลับ
                        </div>
                    </a>

                </div>

                <fieldset disabled>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label for="type_doc" class="form-label">ประเภทเอกสาร</label>
                                <select class="form-select" aria-label="Default select example" id="type_doc"
                                    name="type_doc">
                                    <option value="" {{ empty($data[0]->doc_type) ? 'selected' : '' }}>
                                        เลือกประเภทเอกสาร</option>
                                    <option value="ใบเสร็จเงินเดือน"
                                        {{ $data[0]->doc_type === 'ใบเสร็จเงินเดือน' ? 'selected' : '' }}>ใบเสร็จเงินเดือน
                                    </option>
                                    <option value="ใบรับรองการทำงาร"
                                        {{ $data[0]->doc_type === 'ใบรับรองการทำงาร' ? 'selected' : '' }}>ใบรับรองการทำงาร
                                    </option>

                                </select>

                            </div>

                            <div class="col-6">
                                <label for="doc_lang" class="form-label">ภาษา</label>
                                <select class="form-select" aria-label="Default select example" id="doc_lang"
                                    name="doc_lang">
                                    <option value="" {{ empty($data[0]->doc_lang) ? 'selected' : '' }}>ภาษา</option>
                                    <option value="ไทย" {{ $data[0]->doc_lang === 'ไทย' ? 'selected' : '' }}>ไทย
                                    </option>
                                    <option value="อังกฤษ" {{ $data[0]->doc_lang === 'อังกฤษ' ? 'selected' : '' }}>อังกฤษ
                                    </option>

                                </select>

                            </div>
                        </div>


                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label for="rec_form" class="form-label">รูปแบบการรับ</label>
                                <select class="form-select" aria-label="Default select example" id="rec_form"
                                    name="rec_form">
                                    <option value="" {{ empty($data[0]->doc_pick) ? 'selected' : '' }}>
                                        เลือกรูปแบบการรับ</option>
                                    <option value="รับด้วยตัวเอง"
                                        {{ $data[0]->doc_pick === 'รับด้วยตัวเอง' ? 'selected' : '' }}>รับด้วยตัวเอง
                                    </option>
                                    <option value="ส่งไปรษณี"
                                        {{ $data[0]->doc_pick === 'รับด้วยตัวเอง' ? 'selected' : '' }}>
                                        ส่งไปรษณี (
                                        กรอกที่อยู่ในรายละเอียด )</option>

                                </select>

                            </div>

                            <div class="col-6">
                                <label for="doc_amount" class="form-label">จำนวน</label>
                                <input type="number" class="form-control" id="doc_amount" placeholder="จำนวนกี่ฉบับ ...."
                                    name="doc_amount" value="{{ $data[0]->doc_amount }}">
                            </div>
                        </div>


                    </div>

                    <div class="mb-3">
                        <div class="row">

                            <div class="col-6">
                                <label for="doc_Y" class="form-label">ปี</label>
                                {{-- <input type="number" class="form-control" id="doc_year" placeholder="ปีไหน ...."
                                    name="doc_Y"> --}}
                                <input type="number" name="doc_Y" id="doc_Y" class="date-picker-year form-control"
                                    placeholder="ปีไหน ...." value="{{ $data[0]->doc_Y }}" />
                            </div>

                            <div class="col-6">
                                <label for="doc_M" class="form-label">เดือน</label>

                                <input type="text" id="doc_M" placeholder="เดือนไหน ...." name="doc_M"
                                    class="date-picker-month form-control" value="{{ $data[0]->doc_M }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" name="doc_desc" id="doc_desc">{{ $data[0]->doc_desc }}</textarea>
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
                                    <option value="" {{ empty($data[0]->to_manager) ? 'selected' : '' }}>
                                        เลือกหัวหน้า
                                    </option>
                                    @foreach ($mangerInfo as $items)
                                        <option value="{{ $items->id }}"
                                            {{ $data[0]->to_manager === $items->id ? 'selected' : '' }}>
                                            {{ $items->name }}</option>
                                    @endforeach


                                </select>
                            </div>

                            <div class="col-6">
                                <label for="req_name" class="form-label">ผู้ขอ</label>
                                <input type="text" id="req_name" class="form-control"
                                    value="{{ $data[0]->req_name }}">
                            </div>

                        </div>

                    </div>
                </fieldset>


                @if (auth()->user()->department === 'Manager' && $data[0]->status_form === 'Wait Progress')
                    <div class="mt-5 mb-5 d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-success" id="apv_btn">Approve</button>
                        <button type="submit" id="reject_btn" class="btn btn-danger">Reject</button>
                    </div>



                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea rows="10" class="form-control h-25" placeholder="Leave a comment here" id="doc_reject"></textarea>
                                    <label for="doc_reject">เหตุผลที่ปฎิเสธ ( ไม่ใส่ก็ได้ )</label>
                                </div>
                            </div>

                        </div>

                    </div>
                @endif

                <fieldset disabled>
                    @if ($data[0]->status_form === 'Reject' && $data[0]->reject_desc)
                        <div class="mb-3">
                            <div class="row">
                                <label for="doc_reject" class="form-label">จากผู้รับ : <a class="btn btn-danger">{{ $data[0]->status_form }}</a></label>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea id="doc_reject" rows="10" class="form-control h-25" placeholder="Leave a comment here">{{ $data[0]->reject_desc }}</textarea>
                                        <label for="doc_reject">เหตุผลที่ปฎิเสธ</label>
                                    </div>
                                </div>

                            </div>

                        </div>
                    @endif
                </fieldset>





            </div>
        </form>




    </div>

    <script>
        $(document).ready(function() {
            console.log("Form doc Data => ", {!! json_encode($data) !!})
            // $('#doc_Y').datepicker({
            //     format: 'yyyy',
            //     viewMode: 'years',
            //     minViewMode: 'years',
            //     autoclose: true
            // });


            $('#doc_M').datepicker({
                format: 'MM',
                viewMode: 'months',
                minViewMode: 'months',
                autoclose: true
            });

        });

        function SendData(status) {
            let reject_decs = $('#doc_reject').val()
            let id_form = {!! json_encode($data[0]->doc_id) !!}
            console.log("Form Status ", status)
            console.log("Form id ", id_form)
            console.log("Form Desc ", reject_decs)
            $.ajax({
                url: "{{ route('docMA.post') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'Maction': status,
                    'reject_desc': reject_decs,
                    'table_id': 'doc_id',
                    'form_id': id_form,
                    'form_title': 're_documents'
                },
                dataType: "JSON",
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

                        console.log(response.quary)
                    } else {
                        Swal.fire({
                            title: "Success!",
                            text: "Your Action has been Update.",
                            icon: "success"
                        }).then(() => {
                            console.log(response.success)
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

        $('#apv_btn').on('click', (e) => {
            e.preventDefault()
            let reject_decs = $('#doc_reject').val()
            let id_form = {!! json_encode($data[0]->doc_id) !!}

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Submit"
            }).then((result) => {
                if (result.isConfirmed) {
                    SendData('Approve')


                }
            });

        })

        $('#reject_btn').on('click', (e) => {
            e.preventDefault()
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Submit"
            }).then((result) => {
                if (result.isConfirmed) {
                    SendData('Reject')
                }
            });

        })
    </script>
@endsection
