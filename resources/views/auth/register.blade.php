@extends('layouts.main')
@section('container')
<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-primary bg-gradient-primary" id="btn-add-data" data-toggle="modal" data-target="#modal-user">Tambah Data</button>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-user" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
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
{{-- Modal Tanbah User --}}
@include('auth.modal-user')
<script src="/page-script/user.js"></script>
@endsection
