@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Pilih Siswa yang akan ditagihkan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <select class="form-control select2 js-states" data-toggle="select2" id="pilih-siswa">
                            <option></option>
                            @foreach ($siswa as $row)
                            <option value="{{ $row->unique }}">{{ $row->nis.' - '.$row->nama }}</option>
                            @endforeach
                        </select>
                    </div> <!-- end col -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="row g-0 align-items-center">
                                <div class="col-md-4">
                                    <img src="/img/siswa.png" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h3 class="card-title" id="nama-siswa">Nama Siswa</h3>
                                        <p class="card-text" id="kelas-siswa">Kelas</p>
                                    </div> <!-- end card-body-->
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>Daftar Tagihan</h2>
            </div>
            <div class="card-body">
                <div id="basicwizard">

                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                        <li class="nav-item">
                            <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" id="tab1">
                                <i class="ri-account-circle-line fw-normal fs-20 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">Belum Lunas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" id="tab2">
                                <i class="ri-profile-line fw-normal fs-20 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">Sudah Lunas</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content b-0 mb-0">
                        <div class="tab-pane" id="basictab1">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-tagihan">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Nominal</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="daftar-tagihan">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="basictab2">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-tagihan-lunas">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Nominal</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="daftar-tagihan-lunas">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- tab-content -->
                </div> <!-- end #basicwizard-->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<script src="/page-script/tagihan-siswa.js"></script>
@include('tagihan_siswa.modal-bayar')
@endsection
