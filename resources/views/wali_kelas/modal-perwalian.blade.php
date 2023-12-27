<!-- Modal -->
<div class="modal fade" id="modal-perwalian" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal">Perwalian</h5>
                <button type="button" class="btn-close btn-close-perwalian" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <div class="row mt-2 mb-2">
                    <div class="col-12">
                        <div class="card" style="border-radius:20px">
                            <div class="card-header" style="border-radius:20px 20px 0 0">
                                <h4>Pilih Guru Untuk dijadikan Wali Kelas <span id="kelas-wali"></span></h4>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <input type="hidden" id="unique_perwalian">
                                <input type="hidden" id="unique_kelas">
                                <input type="hidden" value="{{ csrf_token() }}" id="token">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select class="form-control" id="pilih-wali" name="unique_teacher">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="btn-action">
                <button type="button" class="btn btn-primary btn-sm" id="btn-perwalian">Atur Sebagai Wali Kelas</button>
            </div>
        </div>
    </div>
</div>
