@extends('layouts.main')
@section('container')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form>
                    <div class="row">
                        <div class="input-group mb-1 col-sm-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Kelas</span>
                            </div>
                            <select class="form-control" id="kelas" name="kelas">
                                <option value="" selected disabled>Pilih Kelas...</option>
                                @foreach($kelas as $row)
                                <option value="{{ $row->unique }}">{{ $row->kelas.$row->huruf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group mb-1 col-sm-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Tanggal Absen</span>
                            </div>
                            <input type="date" class="form-control" name="tanggal_absen" id="tanggal_absen">
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-md btn-primary" id="btn-add-absen">Absen</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-absen-all" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tanggal Absen</th>
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
<!-- /.row -->
{{-- Modal Siswa --}}
<script src="/page-script/absen-all.js"></script>
@endsection
