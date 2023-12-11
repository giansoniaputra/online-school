<!-- Modal -->
<div class="modal fade" id="modal-siswa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal">Daftar Siswa Kelas <span id="title-kelas"></span></h5>
                <button type="button" class="btn-close-kelas btn-close-siswa" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <div class="row mt-2 mb-2">
                    <div class="col-12">
                        <div class="card" style="border-radius:20px">
                            <div class="card-header" style="border-radius:20px 20px 0 0">
                                <button type="button" id="button-naik-kelas" class="btn btn-primary">Naik Kelas</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <input type="text" id="unique_siswa">
                                <table id="table-siswa" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Select All&nbsp;<input type="checkbox" id="select-all"></th>
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
                </div>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
