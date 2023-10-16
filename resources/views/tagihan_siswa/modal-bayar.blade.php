<!-- Modal -->
<div class="modal fade" id="modal-bayar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <form action="javascript:;">
                    {{-- HIDDEN INPUT --}}
                    {{-- <input type="text" name="unique" id="unique">
                    <input type="text" name="_method" id="method"> --}}
                    <input type="hidden" name="unique_student" id="unique_student">
                    <input type="hidden" name="unique_kelas" id="unique_kelas">
                    <input type="hidden" name="unique_tahun_ajaran" id="unique_tahun_ajaran">
                    <input type="hidden" name="unique_jenis_pembayaran" id="unique_jenis_pembayaran">
                    <input type="hidden" name="unique_generate" id="unique_generate">
                    <input type="hidden" name="periode_tagihan" id="periode_tagihan">
                    <input type="hidden" name="nominal_tagihan" id="nominal_tagihan">
                    <input type="hidden" id="sisa_bayar">
                    @csrf
                    {{-- ./HIDDEN INPUT --}}
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12 mt-2 mb-2">
                            <div class="form-group">
                                <label for="nama">Nama Siswa</label>
                                <input type="text" name="nama" id="nama" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" name="kelas" id="kelas" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="text" id="periode" class="form-control" disabled>
                            </div>
                        </div>
                        {{-- MODAL TAMBAHAN --}}
                        <div class="modal-tambahan"></div>
                        {{-- //MODAL TAMBAHAN --}}
                        <div class="col-sm-12 mb-2">
                            <div class="form-group">
                                <label for="tanggal_bayar">Tanggal Bayar</label>
                                <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="nominal">Nominal</label>
                                <input type="text" name="nominal" id="nominal" class="form-control money" value="0">
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="kembali">Kembali</label>
                                <input type="text" name="kembali" id="kembali" class="form-control money" value="0" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action-bayar">
                <button type="button" class="btn btn-primary" id="save-pembayaran">Bayar</button>
            </div>
        </div>
    </div>
</div>
<script>
    //VANILA JAVASCRIPT
    //Vanila
    let angka = document.querySelector('#nominal')
    angka.addEventListener('keyup', function() {
        let trim = angka.value.trim()
        if (trim.charAt(0) == 0) {
            angka.value = trim.charAt(1);
        }
    })

</script>
