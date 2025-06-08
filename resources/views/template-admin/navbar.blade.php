<ul class="metismenu" id="menu">
    @if(auth()->user()->role == 'pemilik_toko')
        <li class="menu-label text-white">DASHBOARD</li>
        <li>
            <a href="/">
                <div class="parent-icon"><i class='bx bx-home-circle text-white'></i></div>
                <div class="menu-title text-white">DASHBOARD</div>
            </a>
        </li>
        <li>
            <a href="/profil-admin">
                <div class="parent-icon"><i class='bx bx-user text-white'></i></div>
                <div class="menu-title text-white">PROFIL</div>
            </a>
        </li>
        <li class="menu-label text-white">LAPORAN</li>
        <li>
            <a href="/laporan">
                <div class="parent-icon"><i class='bx bx-file text-white'></i></div>
                <div class="menu-title text-white">LAPORAN</div>
            </a>
        </li>
    @else
        <li class="menu-label text-white">DASHBOARD</li>
        <li>
            <a href="/">
                <div class="parent-icon"><i class='bx bx-home-circle text-white'></i></div>
                <div class="menu-title text-white">DASHBOARD</div>
            </a>
        </li>
        <li>
            <a href="/profil-admin">
                <div class="parent-icon"><i class='bx bx-user text-white'></i></div>
                <div class="menu-title text-white">PROFIL</div>
            </a>
        </li>
        <li class="menu-label text-white">DATA</li>
        <li>
            <a href="/satuan">
                <div class="parent-icon"><i class='bx bx-box text-white'></i></div>
                <div class="menu-title text-white">DATA SATUAN</div>
            </a>
        </li>
        <li>
            <a href="/supplier">
                <div class="parent-icon"><i class='bx bx-user text-white'></i></div>
                <div class="menu-title text-white">DATA SUPPLIER</div>
            </a>
        </li>
        <li>
            <a href="/barang_masuk">
                <div class="parent-icon"><i class='bx bx-up-arrow-alt text-success'></i></div>
                <div class="menu-title text-white">DATA BARANG MASUK</div>
            </a>
        </li>
        <li>
            <a href="/barang_keluar">
                <div class="parent-icon"><i class='bx bx-transfer text-danger'></i></div>
                <div class="menu-title text-white">TRANSAKSI BARANG KELUAR</div>
            </a>
        </li>
        <li>
            <a href="/data_barang_keluar">
                <div class="parent-icon"><i class='bx bx-down-arrow-alt text-danger'></i></div>
                <div class="menu-title text-white">DATA BARANG KELUAR</div>
            </a>
        </li>
        <li class="menu-label text-white">LAPORAN</li>
        <li>
            <a href="/laporan">
                <div class="parent-icon"><i class='bx bx-file text-white'></i></div>
                <div class="menu-title text-white">LAPORAN</div>
            </a>
        </li>
        <li class="menu-label text-white">MASTER AKUN PEMILIK</li>
        <li>
            <a href="/master_akun_pemilik">
                <div class="parent-icon"><i class='bx bx-user text-white'></i></div>
                <div class="menu-title text-white">MASTER AKUN PEMILIK</div>
            </a>
        </li>
    @endif
</ul>
