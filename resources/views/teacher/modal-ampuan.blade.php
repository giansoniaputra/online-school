<!-- Modal -->
<div class="modal fade" id="modal-ampu" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3" id="title-modal">Ampuan Mata Pelajaran</h5>
                <button type="button" class="btn-close" id="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- end modal header -->
            <div class="modal-body" style="padding:0 2em">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            {{-- <div class="card-header"></div> --}}
                            <!-- /.card-header -->
                            <div class="card-body">
                                <input type="hidden" id="unique-guru">
                                <table id="table-ampuan" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach($matpels as $matpel)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $matpel->nama_matpel }}</td>
                                            <td>{{ $matpel->kelas }}</td>
                                            <td class="text-center d-flex justify-content-center align-items-center">
                                                <button class="btn btn-sm btn-success text-white ampu-button rounded-circle me-1" title="Ampu" unique-matpel="{{ $matpel->unique }}"><i class=" ri-add-circle-line"></i></button>
                                                <button class="btn btn-sm btn-dangers text-white lepas-button rounded-circle" title="Lepas" unique-matpel="{{ $matpel->unique }}"><i class="ri-close-circle-fill"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col mx-3">
                        <p>Daftar Matapelajaran yang Diampu</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div id="daftar-ampuan" class="mx-4">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="btn-action-ampu"></div>
        </div>
    </div>
</div>
