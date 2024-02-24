@extends('layout.main')

@section('title', 'ฟอร์มขอลา')




@section('contents')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div
            class="fs-5 d-flex justify-content-between align-items-center card-header  text-white {{ $data[0]->status_form === 'Wait Progress' ? 'bg-info' : ($data[0]->status_form === 'Reject' ? 'bg-danger' : 'bg-success') }}">
            ขอลา <a class="btn btn-primary">{{ $data[0]->status_form }}</a>
        </div>

        <form id="leave_View">

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
                                <label for="type_leave" class="form-label">ประเภทการลา</label>
                                <select class="form-select" aria-label="Default select example" id="type_leave"
                                    name="type_leave">
                                    <option value="" {{ empty($data[0]->absence_type) ? 'selected' : '' }}>
                                        เลือกประเภทการลา</option>
                                    <option value="ลาป่วย" {{ $data[0]->absence_type === 'ลาป่วย' ? 'selected' : '' }}>
                                        ลาป่วย
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
                                <label for="to_menager" class="form-label">ถึงใคร</label>
                                <select class="form-select" aria-label="Default select example" id="to_menager"
                                    name="to_menager">
                                    <option value="" {{ empty($data[0]->to_manager) ? 'selected' : '' }}>เลือกหัวหน้า
                                    </option>
                                    @foreach ($mangerInfo as $items)
                                        <option value="{{ $items->id }}"
                                            {{ $data[0]->to_manager === $items->id ? 'selected' : '' }}>
                                            {{ $items->name }}
                                        </option>
                                    @endforeach


                                </select>
                            </div>

                            <div class="col-6">
                                <label for="req_name" class="form-label">ผู้ขอ</label>
                                <input type="text" id="req_name" class="form-control" value="{{ $data[0]->req_name }}">
                            </div>

                        </div>

                    </div>
                </fieldset>

                <div class="mb-3">
                    <div class="col-6">
                        <label for="leave_file" class="form-label">เอกสารแนบ</label>
                        @if ($data[0]->absence_path)
                            <div class="d-flex">
                                <a href="{{ url('storage/uploads/leaveDoc/' . $data[0]->absence_path) }}"
                                    class="btn btn-success" download><i class='bx bx-file' style='color:#ffffff'></i></a>
                            </div>
                        @else
                            {{-- <input type="file" name="leave_file" class="form-control" id="leave_file"> --}}
                            <p>No File Add</p>
                        @endif

                    </div>
                </div>


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
                                <div class="col-12">
                                    <label for="doc_reject" class="form-label">จากผู้รับ : <a class="btn btn-danger">{{ $data[0]->status_form }}</a></label>
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


        function SendData(status) {
            let reject_decs = $('#doc_reject').val()
            let id_form = {!! json_encode($data[0]->absence_id) !!}
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
                    'table_id': 'absence_id',
                    'form_id': id_form,
                    'form_title': 'absences'
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
