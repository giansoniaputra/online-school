@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-siswa" id="btn-add-data">
            <i class="ri-add-box-line"></i>&nbsp;<span>Tambah Siswa</span>
        </button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form action="">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="matkul-judul">Kelas</label>
                                <select class="form-control" id="matkul-judul">
                                    <option value="ALL">SEMUA KELAS</option>
                                    @foreach($kelas as $row)
                                    <option value="{{ $row->unique }}">{{ $row->kelas }}&nbsp;-&nbsp;{{ $row->huruf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-siswa" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>NIS</th>
                            <th>Kelas</th>
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
<!-- /.row -->
{{-- Modal Siswa --}}
@include('student.modal-tambah-data')
<script src="/page-script/siswa.js"></script>
@endsection



{{-- @extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-primary bg-gradient-primary" id="btn-add-data" data-toggle="modal" data-target="#modal-siswa">Tambah Data Siswa</button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form action="">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="matkul-judul">Kelas</label>
                                <select class="form-control" id="matkul-judul">
                                    <option value="ALL">SEMUA KELAS</option>
                                    @foreach($kelas as $row)
                                    <option value="{{ $row->unique }}">{{ $row->kelas }}&nbsp;-&nbsp;{{ $row->huruf }}</option>
@endforeach
</select>
</div>
</div>
</div>
</form>
</div>
<!-- /.card-header -->
<div class="card-body">
    <table id="table-siswa" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
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
<!-- /.row -->
{{-- Modal Siswa --}}
{{-- @include('student.modal-tambah-data')
<script src="/page-script/siswa.js"></script>
@endsection --}}
