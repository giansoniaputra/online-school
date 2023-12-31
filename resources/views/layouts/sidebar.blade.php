<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Online School</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <p class="d-block text-white">{{ auth()->user()->nama }} <span>({{ auth()->user()->role }})</span></p>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if(auth()->user()->role == 'ADMIN')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/student" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/teacher" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/matpel" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mata Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tahun_ajaran" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tahun Ajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/kelas" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/absen_all" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Absen Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(auth()->user()->role == 'GURU')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-school"></i>
                        <p>
                            &nbsp;&nbsp;Akademik
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/absen" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>BAP</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @php
                use App/Models/Teacher;
                $cek_guru = Teacher::where('NPK', auth()->user()->username)->first();
                $cek_wali = WaliKelas::where('unique_wali', $cek_guru)->first();
                @endphp
                @if(auth()->user()->role == 'ADMIN')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user"></i>
                        <p>
                            &nbsp;&nbsp;User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/register" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/roles" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Costum Role</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                {{-- <li class="nav-item">
                    <a href="/student" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Siswa
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/absen" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            BAP
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/matpel" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Mata Pelajaran
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/teacher" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Guru
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/tahun_ajaran" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Tahun Ajaran
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/kelas" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Kelas
                        </p>
                    </a> --}}
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
