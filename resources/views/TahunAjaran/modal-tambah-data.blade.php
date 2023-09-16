<!-- Modal -->
<div class="modal fade" id="modal-tahun-ajaran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <form action="javascript:;">
                    {{-- HIDDEN INPUT --}}
                    <input type="hidden" name="unique" id="unique">
                    <input type="hidden" name="_method" id="method">
                    @csrf
                    {{-- ./HIDDEN INPUT --}}
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12 mb-2 mt-2">
                            <div class="form-group">
                                <label for="tahun_awal">Tahun Awal</label>
                                <input type="number" class="form-control" id="tahun_awal" name="tahun_awal">
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div class="form-group">
                                <label for="tahun_akhir">Tahun Akhir</label>
                                <input type="number" class="form-control" id="tahun_akhir" name="tahun_akhir">
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <select class="form-control" name="periode" id="periode">
                                    <option selected disabled value="">Pilih Jenis Kelamin...</option>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
