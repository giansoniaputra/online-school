<!-- Modal -->
<div class="modal fade" id="modal-kelas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                    <div class="form-group">
                        <label for="kelas">Kelas</label><span class="text-danger">&nbsp;*</span>
                        <input type="number" class="form-control" id="kelas" name="kelas">
                    </div>
                    <div class="form-group">
                        <label for="huruf">Tingkatan</label>
                        <input type="text" class="form-control" id="huruf" name="huruf">
                        <small class="text-success"><i>Misal: A, B, C, dsb... Atau boleh dikosongkan</i></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
