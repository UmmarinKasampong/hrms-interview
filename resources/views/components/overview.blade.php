<ul class="d-flex  gap-0 column-gap-3">
    <li class="">
        <a href="#" class="btn btn-primary d-flex" id="doc">
            ขอเอกสารสำคัญ
            <div class="ms-2 rounded-circle bg-white">
                <span class="p-2 text-dark" id="doc-count">{{$data['0']->re_documents}}</span>
            </div>
        </a>
    </li>

    <li class="">
        <a href="#" class="btn btn-info d-flex" id="leave">
            ขอลา
            <div class="ms-2 rounded-circle bg-white">
                <span class="p-2 text-dark" id="leave-count">{{$data['0']->absences}}</span>
            </div>
        </a>
    </li>

    <li class="">
        <a href="#" class="btn btn-warning d-flex" id="outoff">
            ขอทำงานนอกสถานที่
            <div class="ms-2 rounded-circle bg-white">
                <span class="p-2 text-dark" id="outoff-count">{{$data['0']->offsite_works}}</span>
            </div>
        </a>
    </li>

   
</ul>

<div id="main_menu"></div>

<script type="text/javascript">
    $(document).ready(function() {
        sendData('ขอเอกสารสำคัญ')

        console.log("overall Data => " , {!! json_encode($data) !!})
    });

    $('#doc').on('click', () => {
        let path = 'ขอเอกสารสำคัญ'
        sendData(path)
    })

    $('#leave').on('click', () => {
        let path = 'ขอลา'
        sendData(path)
    })


    $('#outoff').on('click', () => {
        let path = 'ขอทำงานนอกสถานที่'
        sendData(path)
    })



    function sendData(title) {
        // alert(title);
        // let data = new FormData(title)
        $.ajax({
            url: '{{ route('loadMenu.post') }}', // Route to your controller method
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'title': title
            },
            dataType: 'json', // Type of data to expect
       
            success: function(data) {
                // console.log("success " + data.data)
                $('#main_menu').html(data.view); // Update the content of the div with id 'content'
            },
            error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
    }
</script>
