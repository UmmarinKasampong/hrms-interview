<div class="card bg-light">
    <div class="card-header {{ $title === 'ขอเอกสารสำคัญ' ? 'bg-primary' : ($title === 'ขอลา' ? 'bg-info' : 'bg-warning') }} text-white mb-3">
        {{ $title }}
    </div>
    <div class="card-title">
        <ul class="d-flex  gap-0 column-gap-3">

            <li class="" id="all_data">
                <a href="#" class="btn btn-secondary d-flex">
                    All
                    <div class="ms-2 rounded-circle bg-white">
                        <span class="p-2 text-dark" id="all-count">{{$data['0']->ALL_Data}}</span>
                    </div>
                </a>
            </li>



            <li class="" id="wait_data">
                <a href="#" class="btn btn-info d-flex">
                    Wait Progress
                    <div class="ms-2 rounded-circle bg-white">
                        <span class="p-2 text-dark" id="wait-count">{{$data['0']->Wait_Progress}}</span>
                    </div>
                </a>
            </li>

            <li class="" id="reject_data">
                <a href="#" class="btn btn-danger d-flex">
                    Reject
                    <div class="ms-2 rounded-circle bg-white">
                        <span class="p-2 text-dark" id="reject-count">{{$data['0']->Reject}}</span>
                    </div>
                </a>
            </li>

            <li class="" id="apv_data">
                <a href="#" class="btn btn-success d-flex">
                    Approve
                    <div class="ms-2 rounded-circle bg-white">
                        <span class="p-2 text-dark" id="approve-count">{{$data['0']->Approve}}</span>
                    </div>
                </a>
            </li>


            {{-- @if (auth()->user()->department !== 'Manager')
                <li class="" id="add_new">
                    <a href="{{$path}}" class="btn btn-success d-flex">
                        Craete new Data
                    </a>
                </li>
            @endif --}}

        </ul>
    </div>
    <div class="card-body">
        <div id="main_table">

        </div>
    </div>

</div>



<script>
    $(document).ready(function() {
        LoadData('All')
        console.log("Table Info Status => " , {!! json_encode($data) !!})
    });

    
    $('#all_data').on('click', () => {
        let path = 'All'
        LoadData(path)
    })

    $('#wait_data').on('click', () => {
        let path = 'Wait'
        LoadData(path)
    })


    $('#reject_data').on('click', () => {
        let path = 'Reject'
        LoadData(path)
    })

    $('#apv_data').on('click', () => {
        let path = 'Approve'
        // console.log("เตรียม สร้างข้อมูล")
        LoadData(path)
    })


    $('#add_new').on('click', () => {
        let path = 'Approve'
        // LoadData(path)
    })


    function LoadData(quary) {
        // console.log(table)
        $.ajax({
            url: '{{ route('filTable.post') }}', // Route to your controller method
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'quary': quary,
                'table': {!! json_encode($title) !!}
            },
            dataType: 'json', // Type of data to expect

            success: function(res) {
                // console.log("success " + res.table + ' quary ' + res.quary)
                $('#main_table').html(res.view); // Update the content of the div with id 'content'
            },
            error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
    }
</script>
