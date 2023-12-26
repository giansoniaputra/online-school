<!-- Modal -->
<div class="modal fade" id="modal-pdf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal">LAPORAN PRESENSI <span id="title-kelas-laporan"></span> <span id="title-tahun"></span></h5>
                <button type="button" class="btn-close btn-close-laporan" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <iframe id="pdfFrame" width="100%" height="500px" src="{{ url('/generate-pdf') }}"></iframe>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
