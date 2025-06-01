@extends('template-admin.layout')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Barang Masuk</p>
                                    <h4 class="my-1 text-info">{{ $barang_masuk }}</h4>
                                    <p class="mb-0 font-13">Jumlah barang masuk</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                        class='bx bxs-cart-add'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Barang Keluar</p>
                                    <h4 class="my-1 text-danger">{{ $barang_keluar }}</h4>
                                    <p class="mb-0 font-13">Jumlah barang keluar</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                        class='bx bxs-cart-download'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Supplier</p>
                                    <h4 class="my-1 text-success">{{ $supplier }}</h4>
                                    <p class="mb-0 font-13">Jumlah supplier</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                        class='bx bxs-user-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
@endsection
