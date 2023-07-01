<!-- Modal -->
<div class="modal fade" id="modal-guru" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-modal"></h5>
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:;">
                    {{-- HIDDEN INPUT --}}
                    <input type="hidden" name="unique" id="unique">
                    <input type="hidden" name="_method" id="method">
                    @csrf
                    {{-- ./HIDDEN INPUT --}}
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="npk">NPK</label>
                                <input type="text" class="form-control" id="npk" name="npk">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nama_guru">Nama Guru</label>
                                <input type="text" class="form-control" id="nama_guru" name="nama_guru">
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" id="alamat" row pl-3 pr-3s="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="telepon">Nomor Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
