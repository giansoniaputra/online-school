<!-- Modal -->
<div class="modal fade" id="modal-laporan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal">LAPORAN PRESENSI <span id="title-kelas-laporan"></span> <span id="title-tahun"></span></h5>
                <button type="button" class="btn-close btn-close-laporan" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <div class="card mt-3" style="border-radius:20px">
                    <div class="card-header" style="border-radius:20px 20px 0 0">
                        <label class="form-label">FILTER</label>
                    </div>
                    <div class="card-body">
                        <form action="get-laporan" class="d-inline" id="form-cetak-laporan" target="_blank" method="POST">
                            @csrf
                            <div class="row mt-2 mb-2">
                                <input type="hidden" id="unique_tahun_ajaran" name="unique_tahun_ajaran">
                                <input type="hidden" id="unique_kelas" name="unique_kelas">
                                <input type="hidden" id="hari-ini" value="0" name="hari-ini">
                                <div class="col-sm-3 mb-2">
                                    <label for="bulanan" class="form-label">Laporan Bulanan/Satu Periode</label>
                                    <select id="bulanan" class="form-select mt-2" name="bulanan">
                                        <option value="" selected>Pilih Bulan</option>
                                        <option value="ALL">SATU PERIODE</option>
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="col-sm-5 mb-2">
                                    <div class="row">
                                        <label for="tanggal_awal" class="form-label">Laporan Berdasarkan (Tanggal Awal & Tanggal Akhir)</label>
                                        <div class="col-sm-6 mt-2">
                                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal">
                                        </div>
                                        <div class="col-sm-6 mt-2">
                                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="laporan-hari-ini" class="form-label d-block">Laporan Presensi Hari Ini</label>
                                    <button type="button" class="btn btn-primary mt-2" id="laporan-hari-ini">PRESENSI HARI INI</button>
                                    <button type="submit" class="btn btn-danger ms-2 mt-2" id="cetak-laporan-pdf">CETAK</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col-12">
                        <div class="card" style="border-radius:20px">
                            <div class="card-header" style="border-radius:20px 20px 0 0">
                                <label>Laporan Presensi <span id="table-periode"></span> <span id="table-tahun"></span></label>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <div class="row">
                                        <div class="col-12">
                                            <table id="table-laporan" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Siswa</th>
                                                        <th>Hadir</th>
                                                        <th>Izin</th>
                                                        <th>Sakit</th>
                                                        <th>Alfa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
