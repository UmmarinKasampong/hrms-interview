@extends('layout.main')

@section('title', 'Login')




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
    {{-- action={{ route('login.post') }} --}}
    <form method="POST" class="ms-auto me-auto card p-5 mt-5" style="width: 500px" id="login">

        @csrf

        <div class="card-body">

            <div class="mb-5">
                <h2 class="text-center">Login</h2>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" placeholder="Enter Employee Email ...." id="exampleInputEmail1"
                    name="email">

            </div>


            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1"
                    placeholder="Enter Employee Password ...." name="password">
            </div>

           

            <button type="submit" class="btn btn-success mb-3">Submit</button>

            <div class="mb-3">
                <p>do you have account ? <a href="/registration">Registor</a></p>
            </div>
        </div>


    </form>



    <script type="text/javascript">
        $('#login').on('submit', (e) => {
            e.preventDefault()
            let form = $('#login')[0];

            let data = new FormData(form)
            console.log(data)

        

            $.ajax({
                url: "{{ route('login.post') }}",
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


            });
        })
    </script>

@endsection
