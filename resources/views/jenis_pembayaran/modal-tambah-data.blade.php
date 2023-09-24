<!-- Modal -->
<div class="modal fade" id="modal-jenis-pembayaran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <form action="javascript:;">
                    {{-- HIDDEN INPUT --}}
                    <input type="hidden" name="unique" id="unique">
                    <input type="hidden" name="_method" id="method">
                    @csrf
                    {{-- ./HIDDEN INPUT --}}
                    <div class="form-group mt-2 mb-2">
                        <label for="jenis_pembayaran">Jenis Pembayaran</label><span class="text-danger">&nbsp;*</span>
                        <input type="text" class="form-control" id="jenis_pembayaran" name="jenis_pembayaran">
                    </div>
                    <div class="form-group mb-2">
                        <label for="periode">Periode</label><span class="text-danger">&nbsp;*</span>
                        <select class="form-control" name="periode" id="periode">
                            <option selected disabled value="">Pilih Periode...</option>
                            <option value="BULANAN">BULANAN</option>
                            <option value="SEKALI BAYAR">SEKALI BAYAR</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
