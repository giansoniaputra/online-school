$(document).ready(function () {
    let table = $("#table-siswa").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        serverSide: true,
        ajax: {
            url: "/datatablesSiswa",
            type: "GET",
            data: function (d) {
                d.matpel = $("#matkul-judul").val();
            },
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-siswa").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nisn",
            },
            {
                data: "nis",
            },
            {
                data: "nama",
            },
            {
                data: "kelas2",
            },
            {
                data: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            {
                targets: [5], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });

    //Ketika Kelas Dipilih
    $("#matkul-judul").on("change", function () {
        table.ajax.reload();
    });

    //ketika tombol tambah data di klik
    $("#btn-add-data").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#title-modal").html("<p>Tambah Data Siswa</p>");
        $("#btn-action").html(
            '<button type="button" class="btn btn-primary" id="btn-save-data">Simpan</button>'
        );
    });
    //ketika tombol x data di klik
    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#title-modal").html("<p>Data Siswa</p>");
        $("#btn-action").html("");
        //reset form
        $("#nisn").val("");
        $("#nis").val("");
        $("#nama").val("");
        $("#tempat_lahir").val("");
        $("#tanggal_lahir").val("");
        $("#alamat").val("");
        $("#asal_sekolah").val("");
        $("#telepon_ortu").val("");
        $("#agama").val("");
        $("#kelas").val("");
        $("#ayah").val("");
        $("#ibu").val("");
        $("#pekerjaan_ayah").val("");
        $("#pekerjaan_ibu").val("");
        $("#wali").val("");
        $("#pekerjaan_wali").val("");
    });

    $("#modal-siswa").on("click", "#btn-save-data", function () {
        $(this).attr("disabled", "true");
        $("#spinner").html(loader)
        let formdata = $("#modal-siswa form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-siswa form").serialize(),
            url: "/student",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    $("#modal-siswa #btn-save-data").removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $("#modal-siswa #btn-save-data").removeAttr("disabled");
                    $("#spinner").html("")
                    $("#modal-siswa").modal("hide");
                    //reset form
                    $("#nisn").val("");
                    $("#nis").val("");
                    $("#nama").val("");
                    $("#tempat_lahir").val("");
                    $("#tanggal_lahir").val("");
                    $("#alamat").val("");
                    $("#asal_sekolah").val("");
                    $("#telepon_ortu").val("");
                    $("#agama").val("");
                    $("#kelas").val("");
                    $("#ayah").val("");
                    $("#ibu").val("");
                    $("#pekerjaan_ayah").val("");
                    $("#pekerjaan_ibu").val("");
                    $("#wali").val("");
                    $("#pekerjaan_wali").val("");
                    table.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });

    //AMBIL DATA SISWA YANG  AKAN DIEDIT
    $("#table-siswa").on("click", ".edit-siswa-button", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/student/" + unique + "/edit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#spinner").html("")
                $("#nisn").val(response.data.nisn);
                $("#nis").val(response.data.nis);
                $("#nama").val(response.data.nama);
                $("#tempat_lahir").val(response.data.tempat_lahir);
                $("#tanggal_lahir").val(response.data.tanggal_lahir);
                $("#jenis_kelamin").val(response.data.jenis_kelamin);
                $("#alamat").val(response.data.alamat);
                $("#asal_sekolah").val(response.data.asal_sekolah);
                $("#telepon_ortu").val(response.data.telepon_ortu);
                $("#agama").val(response.data.agama);
                $("#kelas").val(response.data.kelas);
                $("#ayah").val(response.data.ayah);
                $("#ibu").val(response.data.ibu);
                $("#pekerjaan_ayah").val(response.data.pekerjaan_ayah);
                $("#pekerjaan_ibu").val(response.data.pekerjaan_ibu);
                $("#wali").val(response.data.wali);
                $("#pekerjaan_wali").val(response.data.pekerjaan_wali);
                //FILL THE HIDDEN INPUT
                $("#unique").val(unique);
                $("#method").val("PUT");

                $("#title-modal").html("<p>Edit Data Siswa</p>");
                $("#btn-action").html(
                    '<button type="button" class="btn btn-primary" id="btn-update-data">Update</button>'
                );
                $("#modal-siswa").modal("show");
            },
        });
    });

    //UPDATE DATA SISWA
    $("#modal-siswa").on("click", "#btn-update-data", function () {
        $(this).attr("disabled", "true");
        $("#spinner").html(loader)
        let formdata = $("#modal-siswa form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-siswa form").serialize(),
            url: "/student/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    $("#modal-siswa #btn-update-data").removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $("#modal-siswa #btn-update-data").removeAttr("disabled");
                    $("#spinner").html("")
                    $("#modal-siswa").modal("hide");
                    //reset form
                    $("#nisn").val("");
                    $("#nis").val("");
                    $("#nama").val("");
                    $("#tempat_lahir").val("");
                    $("#tanggal_lahir").val("");
                    $("#alamat").val("");
                    $("#asal_sekolah").val("");
                    $("#telepon_ortu").val("");
                    $("#agama").val("");
                    $("#kelas").val("");
                    $("#ayah").val("");
                    $("#ibu").val("");
                    $("#pekerjaan_ayah").val("");
                    $("#pekerjaan_ibu").val("");
                    $("#wali").val("");
                    $("#pekerjaan_wali").val("");
                    table.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });

    //HAPUS DATA SISWA
    $("#table-siswa").on("click", ".hapus-siswa-button", function () {
        let unique = $(this).attr("data-unique");
        let token = $(this).attr("data-token");
        Swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Kamu akan menghapus data siswa!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#spinner").html(loader)
                $.ajax({
                    data: {
                        _method: "DELETE",
                        _token: token,
                    },
                    url: "/student/" + unique,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.errors) {
                            $("#spinner").html("")
                            Swal.fire("Warning!", response.errors, "warning");
                        } else {
                            $("#spinner").html("")
                            table.ajax.reload();
                            Swal.fire("Deleted!", response.success, "success");
                        }
                    },
                });
            }
        });
    });

    //Hendler Error
    function displayErrors(errors) {
        // menghapus class 'is-invalid' dan pesan error sebelumnya
        $("input.form-control").removeClass("is-invalid");
        $("select.form-control").removeClass("is-invalid");
        $("div.invalid-feedback").remove();

        // menampilkan pesan error baru
        $.each(errors, function (field, messages) {
            let inputElement = $("input[name=" + field + "]");
            let selectElement = $("select[name=" + field + "]");
            let textAreaElement = $("textarea[name=" + field + "]");
            let feedbackElement = $(
                '<div class="invalid-feedback ml-2"></div>'
            );

            $.each(messages, function (index, message) {
                feedbackElement.append(
                    $('<p class="p-0 m-0 text-center">' + message + "</p>")
                );
            });

            if (inputElement.length > 0) {
                inputElement.addClass("is-invalid");
                inputElement.after(feedbackElement);
            }

            if (selectElement.length > 0) {
                selectElement.addClass("is-invalid");
                selectElement.after(feedbackElement);
            }
            inputElement.each(function () {
                if (inputElement.attr("type") == "text") {
                    inputElement.on("click", function () {
                        $(this).removeClass("is-invalid");
                    });
                    inputElement.on("change", function () {
                        $(this).removeClass("is-invalid");
                    });
                } else if (inputElement.attr("type") == "date") {
                    inputElement.on("change", function () {
                        $(this).removeClass("is-invalid");
                    });
                }
            });
            textAreaElement.each(function () {
                textAreaElement.on("click", function () {
                    $(this).removeClass("is-invalid");
                });
            });
            selectElement.each(function () {
                selectElement.on("click", function () {
                    $(this).removeClass("is-invalid");
                });
            });
        });
    }
});

//VANILA JAVASCRIPT
//Vanila
let angka = document.querySelector('#telepon_ortu')
angka.addEventListener('keyup', function () {
    let trim = angka.value.trim()
    if (trim.charAt(0) == 0) {
        angka.value = ""
    }
})
