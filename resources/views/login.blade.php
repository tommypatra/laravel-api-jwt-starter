<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="{{ asset('template') }}/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login WebApp</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/core.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ asset('template') }}/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('template') }}/assets/js/config.js"></script>
</head>

<body style="display:none;">
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span class="text-primary">
                                        <img src="{{ asset('/images/logo.png') }}" width="100px">
                                    </span>
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Selamat datang di WebApp!</h4>
                        <p class="mb-6">Silahkan masuk dengan akun SIAKAD/ Web anda</p>

                        <form id="formAuthentication" class="mb-6">
                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="input-email"
                                    placeholder="Enter your email" autofocus />
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="input-password"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="remember-me" />
                                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" id="masuk" type="submit">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        <i class='bx bxs-key fs-4'></i>
                                        <span>Masuk Akun Siakad</span>
                                    </span>
                                </button>
                            </div>
                            <div class="mb-6">
                                <div class="btn btn-success d-grid w-100" id="masuk-gmail">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        <i class="bx bxl-google fs-4"></i>
                                        <span>Masuk Gmail Siakad</span>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-6">
                                <div class="btn btn-warning d-grid w-100" id="masuk-akun-web">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        <i class="bx bx-globe fs-4"></i>
                                        <span>Masuk Akun Web</span>
                                    </span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Modal Pilih Role -->
    <div class="modal fade" id="modal-pilih-role" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Role</h5>
                </div>
                <div class="modal-body">
                    <div id="pilih_role"></div>
                    <div class="text-center mt-3 text-muted">
                        Otomatis masuk dalam
                        <strong id="countdown-role">5</strong>
                        detik...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // jika ada dataCallbackGoogle redirect dari login google
        const dataCallbackGoogle = @json($dataCallbackGoogle ?? null);
    </script>

    <!-- Core JS -->
    <script src="{{ asset('template') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <!-- Main JS -->
    <script src="{{ asset('template') }}/assets/js/main.js"></script>
    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- JS konten web -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script src="{{ asset('js/config.js') }}?v={{ filemtime(public_path('js/config.js')) }}"></script>
    <script src="{{ asset('js/auth-clear.js') }}?v={{ filemtime(public_path('js/auth-clear.js')) }}"></script>
    <script src="{{ asset('js/login.js') }}?v={{ filemtime(public_path('js/login.js')) }}"></script>

</body>

</html>
