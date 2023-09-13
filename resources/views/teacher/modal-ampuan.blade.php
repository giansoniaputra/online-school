<!-- Modal -->
<div class="modal fade" id="modal-ampu" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-modal">Mata Pelajaran yang Diampu</h5>
                <button type="button" class="close btn-close-ampu" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"></div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <input type="hidden" id="unique-guru">
                                <table id="table-ampuan" class="table table-bordered table-striped">
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
                                            <td class="text-center">
                                                <button class="btn btn-rounded btn-sm btn-info text-white ampu-button" title="Ampu" unique-matpel="{{ $matpel->unique }}"><i class="fas fa-plus"></i></button>
                                                <button class="btn btn-rounded btn-sm btn-info text-white lepas-button" title="Lepas" unique-matpel="{{ $matpel->unique }}"><i class="fas fa-minus"></i></button>
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
