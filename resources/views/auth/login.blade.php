@auth
    <script>
        window.location.href = "{{ route('index') }}";
    </script>
@endauth

@extends('layouts.none')
@section('content')
    <section class="vh-100 d-flex">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://zotek8.com/wp-content/uploads/2023/06/smartTab-1024x805.png" class="img-fluid"
                        alt="zotek-8">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form class="form-login" method="POST">
                        @csrf
                        <div class="divider d-flex align-items-center my-4 justify-content-center">
                            <h3 class="text-center fw-bold mx-3 mb-0">Login</h3>
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form3Example3">Email</label>
                            <input type="email" id="form3Example3" name="email_username"
                                class="form-control form-control-lg" placeholder="Enter a valid email address" />
                            @if ($errors->has('email_username'))
                                <p>{{ $errors->first('email_username') }}</p>
                            @endif
                        </div>
                        <div class="form-outline mb-3">
                            <label class="form-label" for="form3Example4">Password</label>
                            <input type="password" id="form3Example4" name="password" class="form-control form-control-lg"
                                placeholder="Enter password" />
                            @if ($errors->has('password'))
                                <p>{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check mb-0">
                                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                                <label class="form-check-label" for="form2Example3">
                                    Remember me
                                </label>
                            </div>
                            <a href="#!" class="text-body">Forgot password?</a>
                        </div>
                        @if ($errors->has('login'))
                            <p>{{ $errors->first('login') }}</p>
                        @endif
                        <div class="text-center text-lg-end mt-4 pt-2 ">
                            <button type="submit" class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
