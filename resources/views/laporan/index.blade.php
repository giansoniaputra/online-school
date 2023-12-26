@extends('layouts.velonic')
@section('container-velonic')
<style>
    .card-hover,
    .kelas-baru {
        background-color: #1A2942;
        color: antiquewhite
    }

    .card-hover:hover,
    .kelas-baru:hover {
        background-color: white;
        color: #1A2942;
        cursor: pointer;
    }

</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <table id="table-tahun-ajaran" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran/th>
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
@include('laporan.modal-kelas')
@include('laporan.modal-laporan')
@include('laporan.modal-pdf')
<script src="/page-script/laporan-all.js"></script>
@endsection
