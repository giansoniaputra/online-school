@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-jenis-pembayaran" id="btn-add-data">
            <i class="ri-add-box-line"></i>&nbsp;<span>Tambah Jenis Pembayaran</span>
        </button>
    </div>
    <div class="col-12">
        <div class="card" style="border-radius:20px">
            <div class="card-header" style="border-radius:20px 20px 0 0">
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-generate-tagihan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
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
<script src="/page-script/generate-tagihan.js"></script>
@include('generate_tagihan.modal-generate')
@include('generate_tagihan.modal-siswa')
@endsection
