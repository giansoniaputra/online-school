@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat text-bg-info">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-eye-line widget-icon"></i>
                </div>
                <h6 class="text-uppercase mt-0" title="Siswa">Jumlah Siswa</h6>
                <h2 class="my-2">{{ $data_siswa}}</h2>
                <p class="mb-0">
                    <span class="text-nowrap"><a href="/student" class="small-box-footer text-white fst-italic">More info... <i class="fas fa-arrow-circle-right"></i></a></span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card widget-flat text-bg-success">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-eye-line widget-icon"></i>
                </div>
                <h6 class="text-uppercase mt-0" title="Guru">Jumlah Guru</h6>
                <h2 class="my-2">{{ $data_guru}}</h2>
                <p class="mb-0">
                    <span class="text-nowrap"><a href="/student" class="small-box-footer text-white fst-italic">More info... <i class="fas fa-arrow-circle-right"></i></a></span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- <!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $data_siswa}}</h3>

<p>Data Siswa</p>
</div>
<div class="icon">
    <i class="ion ion-bag"></i>
</div>
<a href="/student" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
</div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
        <div class="inner">
            <h3>{{ $data_guru }}</sup></h3>

            <p>Data Guru</p>
        </div>
        <div class="icon">
            <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/teacher" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- ./col -->
</div> --}}
