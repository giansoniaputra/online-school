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
                        <div class="card" style="border-radius:20px">
                            <div class="card-header" style="border-radius:20px 20px 0 0" id="tambah-bap">
                                <form action="">
                                    <div class="row">
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
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
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
        </div>
    </div>
</div>
@include('absen.modal-tambah-bap')
<!-- /.row -->
{{-- Modal Siswa --}}
<script src="/page-script/absen.js"></script>
@endsection
