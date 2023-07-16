@extends('layouts.main')
@section('container')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-sm bg-gradient-primary" id="btn-add-data" data-toggle="modal" data-target="#modal-siswa">Tambah Data</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-tahun-ajaran" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Awal</th>
                            <th>Tahun Akhir</th>
                            <th>Periode</th>
                            <th>Status</th>
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
@include('TahunAjaran.modal-tambah-data')
<script src="/page-script/tahun-ajaran.js"></script>
@endsection
