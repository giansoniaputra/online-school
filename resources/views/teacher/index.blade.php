@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-guru" id="btn-add-data">
            <i class="ri-add-box-line"></i>&nbsp;<span>Tambah Data</span>
        </button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
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
