@extends('layouts.velonic')
@section('container-velonic')
<style>
    .card-hover {
        background-color: #1A2942;
        color: antiquewhite
    }

    .card-hover:hover {
        background-color: white;
        color: #1A2942;
        cursor: pointer;
    }

</style>
<div class="row" id="list-kelas">
    <div class="col-sm-12 mb-2">
        <div class="card text-start">
            <div class="card-header">
                <h4>Kelas 7</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($kelas7 as $row)
                    <div class="col-sm-4">
                        <div class="card card-hover" data-unique-kelas="{{ $row->unique }}" data-kelas="{{ $row->kelas.$row->huruf }}">
                            <div class="card-body d-flex justify-conten-center flex-column align-items-center">
                                <h3 class="card-title">{{ $row->kelas.$row->huruf }}</h3>
                                <p class="card-text">Klik untuk melihat daftar siswa</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 mb-2">
        <div class="card text-start">
            <div class="card-header">
                <h4>Kelas 8</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($kelas8 as $row)
                    <div class="col-sm-4">
                        <div class="card card-hover" data-unique-kelas="{{ $row->unique }}" data-kelas="{{ $row->kelas.$row->huruf }}">
                            <div class="card-body d-flex justify-conten-center flex-column align-items-center">
                                <h3 class="card-title">{{ $row->kelas.$row->huruf }}</h3>
                                <p class="card-text">Klik untuk melihat daftar siswa</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 mb-2">
        <div class="card text-start">
            <div class="card-header">
                <h4>Kelas 9</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($kelas9 as $row)
                    <div class="col-sm-4">
                        <div class="card card-hover" data-unique-kelas="{{ $row->unique }}" data-kelas="{{ $row->kelas.$row->huruf }}">
                            <div class="card-body d-flex justify-conten-center flex-column align-items-center">
                                <h3 class="card-title">{{ $row->kelas.$row->huruf }}</h3>
                                <p class="card-text">Klik untuk melihat daftar siswa</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 mb-2">
        <div class="card text-start">
            <div class="card-header">
                <h4>Graduation</h4>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
</div>
<!-- /.row -->
{{-- Modal Siswa --}}
<script src="/page-script/histori-kelas.js"></script>
@include('histori_kelas.modal-siswa')
@endsection
