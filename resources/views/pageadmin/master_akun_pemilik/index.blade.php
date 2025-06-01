    @extends('template-admin.layout')

    @section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Master Akun Pemilik</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Master Akun Pemilik</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    @if($data->isEmpty())
                        <a href="{{ route('master_akun_pemilik.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                    @endif
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Profil</th>
                                    <th>Nama</th>
                                    <th>No. WA</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $index => $data)
                                <tr>
                                    <td><img src="{{ asset('profil/' . $data->profil) }}" alt="Profil" style="width: 50px; height: 50px;"></td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->no_wa }}</td>
                                    <td>{{ $data->alamat }}</td>
                                    <td>
                                        <a href="{{ route('master_akun_pemilik.edit', $data->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('master_akun_pemilik.destroy', $data->id) }}" method="POST" style="display:inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Profil</th>
                                    <th>Nama</th>
                                    <th>No. WA</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endsection