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
                            <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Pelanggan</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->

            <div class="row">
                <div class="col-xl-7 mx-auto">
                    <hr />
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-edit me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Edit Pelanggan</h5>
                            </div>
                            <hr>
                            <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <label for="kode_pelanggan" class="form-label">Kode Pelanggan</label>
                                    <input type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan"
                                        value="{{ old('kode_pelanggan', $pelanggan->kode_pelanggan) }}" readonly>
                                    <small class="text-muted">Kode pelanggan tidak dapat diubah</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                                    <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                        value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('nama_pelanggan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="alamat_pelanggan" class="form-label">Alamat Pelanggan</label>
                                    <input type="text" class="form-control" id="alamat_pelanggan" name="alamat_pelanggan"
                                        value="{{ old('alamat_pelanggan', $pelanggan->alamat_pelanggan) }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('alamat_pelanggan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="no_hp_pelanggan" class="form-label">No HP Pelanggan</label>
                                    <input type="text" class="form-control" id="no_hp_pelanggan" name="no_hp_pelanggan"
                                        value="{{ old('no_hp_pelanggan', $pelanggan->no_hp_pelanggan) }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('no_hp_pelanggan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
