@extends('layouts.main')
@section('container')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-sm bg-gradient-primary" id="btn-add-data" data-toggle="modal" data-target="#modal-guru">Tambah Data</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-guru" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NPK</th>
                            <th>Nama Guru</th>
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
@include('teacher.modal-tambah-data')
@include('teacher.modal-ampuan')
<script src="/page-script/guru.js"></script>
@endsection
