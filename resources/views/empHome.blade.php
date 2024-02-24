@extends('layout.main')

@section('title', 'Home Page')




@section('contents')

    <div class="card  ms-auto me-auto mt-5" style="max-width: 1170px; width: 100%;">
        <div class="card-header  text-white bg-primary">
            <div class="d-flex align-items-center p-2 text-white">
                <img src="{{ url('storage/uploads/userImgs/' . auth()->user()->userImg_url) }}" style="width: 100px ;height: 100px" class="rounded-circle me-4" alt="{{ auth()->user()->name }}">
                <div class="fs-4">
                    <h2> {{ auth()->user()->name }}</h2>
                    <span>Position : {{ auth()->user()->department }}</span>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ขอเอกสารสำคัญ</h5>
                            <p class="card-text">เอกสารสำคัญต่างๆ สลิปเงินเดือน เป็นต้น</p>
                            <a href="/docCreate" class="btn btn-primary">ขอเอกสารสำคัญ</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ขอลา</h5>
                            <p class="card-text">ต้องการที่จะลา กรณีเจ็บไข้ ป่วย หรือ มีธุระ</p>
                            <a href="/leaveCreate" class="btn btn-info">ขอลา</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ขอทำงานนอกสถานที่</h5>
                            <p class="card-text">กรณีอยากทำงานข้างนอก ที่ไม่ใช้ในออฟฟิศ</p>
                            <a href="/outCreate" class="btn btn-warning">ขอทำงานนอกสถานที่</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <h5 class="card-title">รายละเอียดการอนุมัติ</h5>
            </div>

            <div class="mt-3">

                <div class="card-title" id="menu_list"></div>
            </div>
           

        </div>
    </div>


    <script>
        $(document).ready(function() {

            function loadData() {
                // alert("โหลด")
                $.ajax({
                    url: '{{ route('overM') }}', // Route to your controller method
                    type: 'GET',
                    dataType: 'json', // Type of data to expect
                    success: function(data) {
                        $('#menu_list').html(data
                        .view); // Update the content of the div with id 'content'
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading data:', error);
                    }
                });
            }

            // Call the function to load data when the page is ready
            loadData();

            $('#loadButton').click(function() {
                loadData(); // Call the function to load the view when the button is clicked
            });
        });
    </script>

@endsection

<style>
    ul {
        text-decoration: none;
        list-style: none;
    }
</style>
