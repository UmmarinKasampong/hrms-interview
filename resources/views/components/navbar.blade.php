@auth
    <nav class="navbar navbar-expand-lg  {{(auth()->user()->department === 'Manager') ? 'bg-danger' : 'bg-primary'}}">
        <div class="container-xl ">
            <a class="navbar-brand fw-bold text-white">{{ config('app.name') }}</a>



           
            <ul class="d-flex justify-content-end navbar-nav" role="search">
                <li class="nav-item">
                    <div class="d-flex align-items-center p-2 text-white">
                        {{-- <img src="{{ url('storage/uploads/userImgs/' . auth()->user()->userImg_url) }}" alt="{{ auth()->user()->name }}"> --}}
                        <img src="{{ url('storage/uploads/userImgs/' . auth()->user()->userImg_url) }}" style="width: 50px ;height: 50px" class="rounded-circle me-2" alt="{{ auth()->user()->name }}">
                        <div class="fs-6">
                            <h5> {{ auth()->user()->name }}</h5>
                            <span>{{ auth()->user()->department }}</span>
                        </div>
                      
                    </div>


                </li>

                <li class="nav-item d-flex align-items-center ">
                    <a href="/logout" class="nav-link btn btn-primary rounded-circle"><i class='bx bxs-log-out fs-2 p-1' style='color:#ffffff'  ></i></a>
                </li>

            </ul>



        </div>
    </nav>
@else
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-xl">
            <a class="navbar-brand" href="#">{{ config('app.name') }}</a>



        </div>
    </nav>

@endauth
