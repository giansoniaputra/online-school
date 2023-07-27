@extends('layouts.main')
@section('container')
<div class="row">
    <div class="col">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">BAP</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Absen</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" id="tambah-bap">
                                <form action="">
                                    <div class="row">
                                        <div class="input-group mb-1 col-sm-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Pertemuan Ke-</span>
                                            </div>
                                            <input type="number" class="form-control" name="bap_per" id="bap_per">
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-md btn-primary" id="btn-add-bap">Tambah BAP</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="table-bap" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Pertemuan</th>
                                            <th>Tanggal BAP</th>
                                            <th>Berita Acara</th>
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
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="input-group mb-1 col-sm-3">
                                    <input type="text" id="bap_unique_now">
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
        </div>
    </div>
</div>
@include('absen.modal-tambah-bap')
<!-- /.row -->
{{-- Modal Siswa --}}
<script src="/page-script/absen.js"></script>
@endsection
