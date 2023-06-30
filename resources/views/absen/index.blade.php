@extends('layouts.main')
@section('container')
<div class="row">
    <div class="col">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">BAP</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Absen</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                <h3>Konten Tab 1</h3>
                <p>Ini adalah konten dari Tab 1.</p>
            </div>
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                {{-- <div class="input-group mb-1 col-sm-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Pilih Tanggal</span>
                                    </div>
                                    <input type="date" class="form-control" placeholder="Username">
                                </div> --}}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="table-absen" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
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
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
{{-- Modal Siswa --}}
<script src="/page-script/absen.js"></script>
@endsection
