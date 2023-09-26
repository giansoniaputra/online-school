<!-- Modal -->
<div class="modal fade" id="modal-bap" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="pertemuan">Pertemuan Ke-</label>
                                <input type="text" class="form-control" id="pertemuan" name="pertemuan" readonly>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="tahun_ajaran_unique" name="tahun_ajaran_unique" readonly>
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="matpel_unique">Mata Pelajaran</label>
                                <select class="form-control" name="matpel_unique" id="matpel_unique">
                                    <option selected disabled value="">Pilih Mata Pelajaran...</option>
                                    @foreach($matpels as $matpel)
                                    <option value="{{ $matpel->matpel_unique }}">{{ $matpel->nama_matpel }}&nbsp;-&nbsp;{{ $matpel->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="bap">Berita Acara</label>
                                <textarea class="form-control" name="bap" id="bap" row pl-3 pr-3s="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action-bap"></div>
        </div>
    </div>
</div>
