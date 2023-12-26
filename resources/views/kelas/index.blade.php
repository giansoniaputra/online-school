@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-kelas" id="btn-add-data">
            <i class="ri-add-box-line"></i>&nbsp;<span>Tambah Kelas</span>
        </button>
    </div>
    <div class="col-12">
        <div class="card" style="border-radius:20px">
            <div class="card-header" style="border-radius:20px 20px 0 0">
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-kelas" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Tingkat</th>
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
@include('kelas.modal')
<script src="/page-script/kelas.js"></script>
@endsection
