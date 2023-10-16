<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Online School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg position-relative">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="assets/images/auth-img.jpg" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="auth-brand p-4">
                                        <a href="index.html" class="logo-light">
                                            <img src="assets/images/logo.png" alt="logo" height="22">
                                        </a>
                                        <a href="index.html" class="logo-dark">
                                            <img src="assets/images/logo-dark.png" alt="dark logo" height="22">
                                        </a>
                                    </div>
                                    <div class="p-4 my-auto" id="login">
                                        <h4 class="fs-20">Sign In</h4>
                                        <p class="text-muted mb-3">Masukan Username dan Password</p>
                                        @if(session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible text-bg-danger border-0 fade show time-alert" role="alert">
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                            <small>{{ session('error') }}</small>
                                        </div>
                                        @endif

                                        <!-- form -->
                                        <form action="/authenticate" method="post">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input class="form-control @error('username') is-invalid @enderror" type="text" id="username" name="username" placeholder="Enter your Username">
                                                @error('username')
                                                <div class="invalid-feedback">
                                                    <small class="fst-italic">{{ $message }}</small>
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" placeholder="Enter your password" name="password">
                                                @error('password')
                                                <div class="invalid-feedback">
                                                    <small class="fst-italic">{{ $message }}</small>
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="mb-0 text-start">
                                                <button class="btn btn-soft-primary w-100" type="submit" id="login-btn"><i class="ri-login-circle-fill me-1"></i> <span class="fw-bold">Log In</span> </button>
                                            </div>

                                            {{-- <div class="text-center mt-4">
                                                <p class="text-muted fs-16">Sign in with</p>
                                                <div class="d-flex gap-2 justify-content-center mt-3">
                                                    <a href="javascript: void(0);" class="btn btn-soft-primary"><i class="ri-facebook-circle-fill"></i></a>
                                                    <a href="javascript: void(0);" class="btn btn-soft-danger"><i class="ri-google-fill"></i></a>
                                                    <a href="javascript: void(0);" class="btn btn-soft-info"><i class="ri-twitter-fill"></i></a>
                                                    <a href="javascript: void(0);" class="btn btn-soft-dark"><i class="ri-github-fill"></i></a>
                                                </div>
                                            </div> --}}
                                        </form>
                                        <!-- end form-->
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark">
            <script>
                document.write(new Date().getFullYear())

            </script> © Velonic - Theme by Techzaa
        </span>
    </footer>
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <script>
        setInterval(() => {
            $("#login .time-alert").remove();
        }, 5000);
        // let login = document.querySelector('#login-btn');
        // login.addEventListener('click', function(e) {
        //     login.setAttribute("disabled", "true");
        // })

    </script>

</body>

</html>
{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Online School</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <h3>ONLINE SCHOOL</h3>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="/authenticate" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukan Username" name="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            @error('username') {{ $message }} @enderror
</div>
</div>
<div class="input-group mb-3">
    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukan Password" name="password">
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
    </div>
    <div class="invalid-feedback">
        @error('password') {{ $message }} @enderror
    </div>
</div>
<div class="row">
    <div class="col-8">
        <div class="icheck-primary">
            <input type="checkbox" id="remember">
            <label for="remember">
                Remember Me
            </label>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-4">
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </div>
    <!-- /.col -->
</div>
</form>
</div>
<!-- /.login-card-body -->
</div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>

</html> --}}
