@extends('layouts.velonic')
@section('container-velonic')
{{-- --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title mb-0"> </h4>
            </div>
            <div class="card-body">
                <div id="basicwizard">

                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                        <li class="nav-item">
                            <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" id="tab1">
                                <i class="ri-account-circle-line fw-normal fs-20 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">Input Agenda</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" id="tab2">
                                <i class="ri-profile-line fw-normal fs-20 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">Absen Siswa</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content b-0 mb-0">
                        <div class="tab-pane" id="basictab1">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card" style="border-radius:20px">
                                        <div class="card-header" style="border-radius:20px 20px 0 0" id="tambah-bap">
                                            <form action="">
                                                <div class="input-group mb-1 col-sm-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Pertemuan Ke-</span>
                                                    </div>
                                                    <input type="number" class="form-control" name="bap_per" id="bap_per">
                                                </div>
                                                <div class="input-group mb-1 col-sm-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">T.Ajaran</span>
                                                    </div>
                                                    <select class="form-control" id="tahun_ajaran">
                                                        @foreach($tahun_ajaran as $row)
                                                        <option value="{{ $row->unique }}" {{ ($row->unique == $tahun_aktif->unique ? 'selected' : '') }}>{{ $row->tahun_awal }}/{{ $row->tahun_akhir }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-md btn-primary" id="btn-add-bap">Tambah BAP</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="table-bap" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Mata Pelajaran </th>
                                                        <th>Kelas </th>
                                                        <th>Pertemuan</th>
                                                        <th>Tahun Ajaran</th>
                                                        <th>Tanggal BAP</th>
                                                        <th>Berita Acara</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Filters</th>
                                                        <th>
                                                            <select class="form-control" id="filter-matpels">
                                                                <option value="ALL">Semua Mata Pelajaran</option>
                                                                @foreach($matpels as $matpel)
                                                                <option value="{{ $matpel->matpel_unique }}">{{ $matpel->nama_matpel }}&nbsp;-&nbsp;{{ $matpel->kelas }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th>Kelas </th>
                                                        <th>Pertemuan</th>
                                                        <th>Tahun Ajaran</th>
                                                        <th>Tanggal BAP</th>
                                                        <th>Berita Acara</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>

                        <div class="tab-pane" id="basictab2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header row">
                                            <input type="hidden" id="bap_unique_now">
                                            <div class="mb-1 col-sm-4">
                                                <p>Mata Pelajaran: <span id="matpel-judul">&nbsp;</span></p>
                                            </div>
                                            <div class="mb-1 col-sm-4">
                                                <p>Tahun Ajaran: <span id="tahun-ajaran-judul">&nbsp;</span></p>
                                            </div>
                                            <div class="mb-1 col-sm-4">
                                                <p>Tanggal Absen: <span id="tanggal-absen-judul">&nbsp;</span></p>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="table-absen" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Siswa</th>
                                                        <th>Kelas</th>
                                                        <th>Kehadiran</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>

                    </div> <!-- tab-content -->
                </div> <!-- end #basicwizard-->

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->
{{-- --}}
@include('absen.modal-tambah-bap')
<!-- /.row -->
{{-- Modal Siswa --}}
<script src="/page-script/absen.js"></script>
@endsection
