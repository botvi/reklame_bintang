<ul class="metismenu" id="menu">
    @if(auth()->user()->role == 'pemilik_toko')
        <li class="menu-label text-black">DASHBOARD</li>
        <li>
            <a href="/dashboard">
                <div class="parent-icon"><i class='bx bx-home-circle text-black'></i></div>
                <div class="menu-title text-black">DASHBOARD</div>
            </a>
        </li>
        <li>
            <a href="/profil-admin">
                <div class="parent-icon"><i class='bx bx-user text-black'></i></div>
                <div class="menu-title text-black">PROFIL</div>
            </a>
        </li>
        <li class="menu-label text-black">LAPORAN</li>
        <li>
            <a href="/laporan">
                <div class="parent-icon"><i class='bx bx-file text-black'></i></div>
                <div class="menu-title text-black">LAPORAN</div>
            </a>
        </li>
    @elseif(auth()->user()->role == 'kasir_toko')
        <li class="menu-label text-black">DASHBOARD</li>
        <li>
            <a href="/dashboard">
                <div class="parent-icon"><i class='bx bx-home-circle text-black'></i></div>
                <div class="menu-title text-black">DASHBOARD</div>
            </a>
        </li>
        <li>
            <a href="/profil-admin">
                <div class="parent-icon"><i class='bx bx-user text-black'></i></div>
                <div class="menu-title text-black">PROFIL</div>
            </a>
        </li>
        <li class="menu-label text-black">DATA</li>
        <li>
            <a href="/pelanggan">
                <div class="parent-icon"><i class='bx bx-user-circle text-black'></i></div>
                <div class="menu-title text-black">DATA PELANGGAN</div>
            </a>
        </li>
        <li>
            <a href="/barang_keluar">
                <div class="parent-icon"><i class='bx bx-log-out text-danger'></i></div>
                <div class="menu-title text-black">TRANSAKSI BARANG KELUAR</div>
            </a>
        </li>
        <li>
            <a href="/data_barang_keluar">
                <div class="parent-icon"><i class='bx bx-list-ul text-danger'></i></div>
                <div class="menu-title text-black">DATA BARANG KELUAR</div>
            </a>
        </li>
    @else
        <li class="menu-label text-black">DASHBOARD</li>
        <li>
            <a href="/dashboard">
                <div class="parent-icon"><i class='bx bx-home-circle text-black'></i></div>
                <div class="menu-title text-black">DASHBOARD</div>
            </a>
        </li>
        <li>
            <a href="/profil-admin">
                <div class="parent-icon"><i class='bx bx-user text-black'></i></div>
                <div class="menu-title text-black">PROFIL</div>
            </a>
        </li>
        <li class="menu-label text-black">DATA</li>
        <li>
            <a href="/satuan">
                <div class="parent-icon"><i class='bx bx-box text-black'></i></div>
                <div class="menu-title text-black">DATA SATUAN</div>
            </a>
        </li>
        <li>
            <a href="/supplier">
                <div class="parent-icon"><i class='bx bx-user text-black'></i></div>
                <div class="menu-title text-black">DATA SUPPLIER</div>
            </a>
        </li>
      
        <li>
            <a href="/barang">
                <div class="parent-icon"><i class='bx bx-box text-black'></i></div>
                <div class="menu-title text-black">DATA BARANG</div>
            </a>
        </li>
        <li>
            <a href="/barang_masuk">
                <div class="parent-icon"><i class='bx bx-up-arrow-alt text-success'></i></div>
                <div class="menu-title text-black">DATA BARANG MASUK</div>
            </a>
        </li>
        <li>
            <a href="/barang_keluar">
                <div class="parent-icon"><i class='bx bx-log-out text-danger'></i></div>
                        <div class="menu-title text-black">TRANSAKSI BARANG KELUAR</div>
            </a>
        </li>
        <li>
            <a href="/data_barang_keluar">
                <div class="parent-icon"><i class='bx bx-list-ul text-danger'></i></div>
                <div class="menu-title text-black">DATA BARANG KELUAR</div>
            </a>
        </li>
        <li class="menu-label text-black">LAPORAN</li>
        <li>
            <a href="/laporan">
                <div class="parent-icon"><i class='bx bx-file text-black'></i></div>
                <div class="menu-title text-black">LAPORAN</div>
            </a>
        </li>
        <li class="menu-label text-black">MASTER AKUN</li>
        <li>
            <a href="/master_akun_pemilik">
                <div class="parent-icon"><i class='bx bx-user text-black'></i></div>
                <div class="menu-title text-black">MASTER AKUN</div>
            </a>
        </li>
    @endif
</ul>
