<!-- Modal -->
<div class="modal fade" id="modal-siswa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
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
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nisn">NISN</label>
                                <input type="text" class="form-control" id="nisn" name="nisn">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nis">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis">
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nama">Nama Siswa</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="jenis_kelamin">Gender</label>
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    <option selected disabled value="">Pilih Jenis Kelamin...</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perembuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" id="alamat" row pl-3 pr-3 mb-2 my-2s="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="asal_sekolah">Asal Sekolah</label>
                                <input type="text" class="form-control" name="asal_sekolah" id="asal_sekolah">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="telepon_ortu">Nomor Telepon Orang Tua</label>
                                <input type="number" class="form-control" name="telepon_ortu" id="telepon_ortu">
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="agama">Agama</label>
                                <select class="form-control" name="agama" id="agama">
                                    <option selected disabled value="">Pilih Agama Siswa...</option>
                                    <option value="ISLAM">ISLAM</option>
                                    <option value="KRISTEN">KRISTEN</option>
                                    <option value="PROTESTAN">PROTESTAN</option>
                                    <option value="HINDU">HINDU</option>
                                    <option value="BUDHA">BUDHA</option>
                                    <option value="KONGHUCHU">KONGHUCHU</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <select class="form-control" name="kelas" id="kelas">
                                    <option selected disabled value="">Pilih Kelas Siswa...</option>
                                    @foreach ($kelas as $row)
                                    <option value="{{ $row->unique }}">{{ $row->kelas.$row->huruf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="ayah">Ayah</label>
                                <input type="text" class="form-control" id="ayah" name="ayah">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="ibu">Ibu</label>
                                <input type="text" class="form-control" id="ibu" name="ibu">
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                                <input type="text" class="form-control" id="pekerjaan_ayah" name="pekerjaan_ayah">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                <input type="text" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu">
                            </div>
                        </div>
                    </div>
                    <div class="row pl-3 pr-3 mb-2 my-2">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="wali">Wali</label>
                                <input type="text" class="form-control" id="wali" name="wali">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pekerjaan_wali">Pekerjaan Wali</label>
                                <input type="text" class="form-control" id="pekerjaan_wali" name="pekerjaan_wali">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action"></div>
        </div>
    </div>
</div>
