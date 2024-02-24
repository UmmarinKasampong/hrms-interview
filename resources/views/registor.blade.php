@extends('layout.main')

@section('title', 'Registor')




@section('contents')
    {{-- <div class="mt-5">
        @if ($errors->any())
            <div class="col-12">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div> --}}

    {{-- action={{ route('registration.post') }} --}}
    <form method="POST" class="ms-auto me-auto card p-3 mt-5" style="width: 500px" id="register">

        @csrf


        <div class="card-body">

            <div class="mb-5">
                <h2 class="text-center">Registor</h2>
            </div>


            <div class="mb-3">
                <label for="fullname" class="form-label">Full name</label>
                <input type="text" class="form-control" id="fullname" placeholder="Enter employee Full Name ...."
                    name="fullname">

            </div>

            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <select class="form-select" aria-label="Default select example" id="position" name="position">
                    <option selected>Select Position.....</option>
                    <option value="Manager">Manager</option>
                    <option value="Frontend Dev">Frontend Dev</option>
                    <option value="Backend Dev">Backend Dev</option>
                </select>

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter employee Email ...."
                    name="email">

            </div>


            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter employee Password ...."
                    name="password">
            </div>


            <div class="mb-3 d-flex justify-content-between">
                <label for="img_user" class="fs-2 border "><i class='bx bx-cloud-upload p-2'></i></label>
                <input type="file" name="img_user" id="img_user" class="d-none">
                <img style="width: 50px ;height: 50px" class="rounded-circle" src="" id="previewImage" alt="Preview Image">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>




    <script type="text/javascript">
        $(document).ready(function() {
            $('#previewImage').hide()
        })


        $('#img_user').on('change' , ()=> {
            let file = $("#img_user")[0].files[0];
            console.log(file)
            if (file) {
                $('#previewImage').show()
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Set the source of the image element to the data URL
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        })
        $('#register').on('submit', (e) => {
            e.preventDefault()
            let form = $('#register')[0];

            let data = new FormData(form)


    
            $.ajax({
                url: "{{ route('registration.post') }}",
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
                        iziToast.success({

                            message: response.success,
                            position: 'topCenter'
                        });
                        window.location.href = response.redirect;
                    }

                },
                // error: function(xhr, status, error) {
                //         iziToast.error({

                //             message: error,
                //             position: 'topCenter'
                //         });
                // }


            });
        })
    </script>
@endsection
