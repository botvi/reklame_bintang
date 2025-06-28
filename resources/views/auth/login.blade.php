<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('env') }}/logo.png" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('admin') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ asset('admin') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ asset('admin') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('admin') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('admin') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('admin') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('admin') }}/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('admin') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('admin') }}/assets/css/icons.css" rel="stylesheet">
    <title>Login - Nadia Bangunan</title>
    <style>
        .section-authentication-signin {
            min-height: 100vh;
        }

        img.img-fluid {
            max-height: 300px;
        }

        .login-container {
            margin: 0 auto;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }

        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .info-card h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .info-card .number {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }

        .table-sm td, .table-sm th {
            padding: 0.5rem;
            font-size: 12px;
        }

        .badge {
            font-size: 10px;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .table-responsive {
            border: none;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,.075);
        }
    </style>

</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">

        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
                <div class="row align-items-center">
                
                    <!-- Form -->
                    <div class="login-container col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <img src="{{ asset('env') }}/logo_text.png" width="300" alt="Logo Nadia Bangunan"
                                            class="img-fluid">
                                    </div>

                                    <!-- Informasi Kadaluarsa -->
                                    @if($totalBarang > 0)
                                    <div class="info-card">
                                        <h5><i class="bx bx-warning"></i> Peringatan Kadaluarsa</h5>
                                        <div class="number">{{ $totalBarang }}</div>
                                        <small>Barang mendekati kadaluarsa</small>
                                        <div class="mt-2">
                                            <strong>Total Nilai: Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>

                                    <!-- Detail Barang Kadaluarsa -->
                                    {{-- <div class="card mb-3">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0"><i class="bx bx-list-ul"></i> Detail Barang Kadaluarsa</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Nama Barang</th>
                                                            <th>Sisa Stok</th>
                                                            <th>Sisa Hari</th>
                                                            <th>Nilai</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($barangMasuk->take(5) as $barang)
                                                        <tr class="{{ $barang->sisa_hari < 0 ? 'table-danger' : ($barang->sisa_hari <= 3 ? 'table-warning' : 'table-info') }}">
                                                            <td>
                                                                <small>{{ $barang->nama_barang }}</small>
                                                                <br>
                                                                <span class="badge bg-secondary">{{ $barang->supplier->nama_supplier ?? '-' }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge {{ $barang->sisa_stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $barang->sisa_stok }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if($barang->sisa_hari < 0)
                                                                    <span class="badge bg-danger">Kadaluarsa</span>
                                                                @elseif($barang->sisa_hari <= 3)
                                                                    <span class="badge bg-warning">{{ $barang->sisa_hari }} hari</span>
                                                                @else
                                                                    <span class="badge bg-info">{{ $barang->sisa_hari }} hari</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small>Rp {{ number_format($barang->total_nilai, 0, ',', '.') }}</small>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if($barangMasuk->count() > 5)
                                            <div class="card-footer text-center">
                                                <small class="text-muted">Menampilkan 5 dari {{ $barangMasuk->count() }} barang</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div> --}}
                                    @endif

                                    <div class="login-separater text-center mb-4">
                                        <span>MASUK MENGGUNAKAN USERNAME DAN PASSWORD</span>
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username"
                                                    id="username" placeholder="Username" required>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="password" name="password" placeholder="Enter Password"> <a
                                                        href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>
                                            </div>

                                            {{-- <!-- reCAPTCHA -->
                                            {!! NoCaptcha::display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                         --}}
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bxs-lock-open"></i> Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                        {{-- {!! NoCaptcha::renderJs() !!} --}}

                                    </div>

                                    <!-- Informasi Kontak -->
                                    @if(isset($pemilikToko) && $pemilikToko->no_wa != '-')
                                    <div class="text-center mt-3">
                                        <hr>
                                        <small class="text-muted">
                                            <i class="bx bx-phone"></i> Kontak: {{ $pemilikToko->nama }} - {{ $pemilikToko->no_wa }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Form -->

                </div>
            </div>
        </div>
    </div>

    <!--end wrapper-->
    <!-- Bootstrap JS -->
    @include('sweetalert::alert')

    <script src="{{ asset('admin') }}/assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="{{ asset('admin') }}/assets/js/jquery.min.js"></script>
    <script src="{{ asset('admin') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ asset('admin') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="{{ asset('admin') }}/assets/js/app.js"></script>
</body>

</html>
