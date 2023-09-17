@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" id="btn-add-data" data-toggle="modal" data-target="#modal-siswa">
            <i class="ri-add-box-line"></i>&nbsp;<span>Tambah Tahun Ajaran</span>
        </button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
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
