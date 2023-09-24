@extends('layouts.velonic')
@section('container-velonic')
<div class="row">
    {{-- <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-jenis-pembayaran" id="btn-add-data">
            <i class="ri-add-box-line"></i>&nbsp;<span>Tambah Jenis Pembayaran</span>
        </button>
    </div> --}}
    <div class="col-12">
        <div class="card" style="border-radius:20px">
            <div class="card-header" style="border-radius:20px 20px 0 0" id="form-setting">
                <form action="javascript:;" class="d-inline">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="unique_jenis_pembayaran">Jenis Pembayaran</label>
                                @csrf
                                <select class="form-control" name="unique_jenis_pembayaran" id="unique_jenis_pembayaran">
                                    <option selected disabled value="">Pilih Jenis Pembayaran...</option>
                                    @foreach ($jenis_pembayaran as $row)
                                    <option value="{{ $row->unique }}">{{ $row->jenis_pembayaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="unique_tahun_ajaran">Tahun Ajaran</label>
                                <select class="form-control" name="unique_tahun_ajaran" id="unique_tahun_ajaran">
                                    <option selected disabled value="">Pilih Tahun Ajaran...</option>
                                    @foreach ($tahun_ajaran as $row)
                                    <option value="{{ $row->unique }}">{{ $row->tahun_awal }}/{{ $row->tahun_akhir }}&nbsp;{{ $row->periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label></label>
                                <br>
                                <div id="action-button"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table-setting-tagihan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Nominal</th>
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
<script src="/page-script/setting-tagihan.js"></script>
@endsection
