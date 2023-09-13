$(document).ready(function () {
    let table = $("#table-tahun-ajaran").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        serverSide: true,
        ajax: "/datatablesTahunAjaran",
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-tahun-ajaran")
                        .DataTable()
                        .page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "tahun_awal",
            },
            {
                data: "tahun_akhir",
            },
            {
                data: "periode",
            },
            {
                data: "status",
                render: function (data, type, row, meta) {
                    if (data == "1") {
                        return type === "display"
                            ? '<button class="badge bg-success border-0">Aktif</button>'
                            : data;
                    } else {
                        return type === "display"
                            ? '<button class="badge bg-danger border-0">Tidak Aktif</button>'
                            : data;
                    }
                },
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
                targets: [4], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });
    //Reset Form
    $(".btn-close").on("click", function () {
        $("#title-modal").html("");
        $("#modal-tahun-ajaran #btn-action").html("");
        $("#tahun_awal").val("");
        $("#tahun_akhir").val("");
        $("#periode").val("");
        $("#unique").val("");
        $("#method").val("");
    });
    //KEtika tombol tambah data di click
    $("#btn-add-data").on("click", function () {
        $("#title-modal").html("Tambah Data Tahun Ajaran");
        $("#modal-tahun-ajaran #btn-action").html(
            '<button type="button" class="btn btn-sm bg-gradient-primary" id="btn-save" data-toggle="modal" data-target="#modal-siswa">Tambah</button>'
        );
        $("#modal-tahun-ajaran").modal("show");
    });
    //Action Simpan
    $("#modal-tahun-ajaran").on("click", "#btn-save", function () {
        $(this).attr("disabled", "true");
        $("#spinner").html(loader)
        let formdata = $("#modal-tahun-ajaran form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-tahun-ajaran form").serialize(),
            url: "/tahun_ajaran",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    $("#modal-tahun-ajaran #btn-save").removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $("#modal-tahun-ajaran #btn-save").removeAttr("disabled");
                    $("#spinner").html("")
                    $("#unique").val("");
                    $("#method").val("");
                    $("#title-modal").html("");
                    $("#modal-tahun-ajaran #btn-action").html("");
                    $("#tahun_awal").val("");
                    $("#tahun_akhir").val("");
                    $("#periode").val("");
                    table.ajax.reload();
                    $("#modal-tahun-ajaran").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            },
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

            $(".btn-close").on("click", function () {
                inputElement.each(function () {
                    $(this).removeClass("is-invalid");
                });
                textAreaElement.each(function () {
                    $(this).removeClass("is-invalid");
                });
                selectElement.each(function () {
                    $(this).removeClass("is-invalid");
                });
            });

            $.each(messages, function (index, message) {
                feedbackElement.append(
                    $('<p class="p-0 m-0 text-center">' + message + "</p>")
                );
            });
            console.log(inputElement.length);
            if (inputElement.length > 0) {
                inputElement.addClass("is-invalid");
                inputElement.after(feedbackElement);
            }

            if (selectElement.length > 0) {
                selectElement.addClass("is-invalid");
                selectElement.after(feedbackElement);
            }
            if (textAreaElement.length > 0) {
                textAreaElement.addClass("is-invalid");
                textAreaElement.after(feedbackElement);
            }
            inputElement.each(function () {
                if (
                    inputElement.attr("type") == "text" ||
                    inputElement.attr("type") == "number"
                ) {
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
    //Ambil Data yang akan di edit
    $("#table-tahun-ajaran").on("click", ".button-edit", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        $("#unique").val(unique);
        $("#method").val("PUT");
        $("#title-modal").html("Edit Data Tahun Ajaran");
        $("#modal-tahun-ajaran #btn-action").html(
            '<button type="button" class="btn btn-sm bg-gradient-primary" id="btn-update" data-toggle="modal" data-target="#modal-siswa">Update</button>'
        );
        $.ajax({
            data: {
                unique: unique,
            },
            url: "/tahun_ajaran/" + unique + "/edit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#spinner").html("")
                $("#tahun_awal").val(response.data.tahun_awal);
                $("#tahun_akhir").val(response.data.tahun_akhir);
                $("#periode").val(response.data.periode);
                $("#modal-tahun-ajaran").modal("show");
            },
        });
    });
    //Action Update
    $("#modal-tahun-ajaran").on("click", "#btn-update", function () {
        $(this).attr("disabled", "true");
        $("#spinner").html(loader)
        let formdata = $("#modal-tahun-ajaran form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-tahun-ajaran form").serialize(),
            url: "/tahun_ajaran/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    $("#modal-tahun-ajaran #btn-update").removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $("#spinner").html("")
                    $("#title-modal").html("");
                    $("#modal-tahun-ajaran #btn-action").html("");
                    $("#tahun_awal").val("");
                    $("#tahun_akhir").val("");
                    $("#periode").val("");
                    table.ajax.reload();
                    $("#modal-tahun-ajaran").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });

    //Action Hapus
    $("#table-tahun-ajaran").on("click", ".button-delete", function () {
        let unique = $(this).attr("data-unique");
        let token = $(this).attr("data-token");

        Swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Kamu akan menghapus data tahun ajaran!",
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
                        _token: token,
                        _method: "DELETE",
                    },
                    url: "/tahun_ajaran/" + unique,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.errors) {
                            $("#spinner").html("")
                            Swal.fire("Warning!", response.errors, "warning");
                        } else {
                            $("#spinner").html("")
                            table.ajax.reload();
                            Swal.fire("Good job!", response.success, "success");
                        }
                    },
                });
            }
        });
    });
    //Mengubah Tahun Aktif
    $("#table-tahun-ajaran").on("click", ".button-aktif", function () {
        let unique = $(this).attr("data-unique");
        Swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Kamu akan mengubah tahun ajaran aktif!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Ubah!",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#spinner").html(loader)
                $.ajax({
                    data: {
                        unique: unique,
                    },
                    url: "/changeTahunAjaran",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $.ajax({
                            url: "/refreshTahunAjaran",
                            type: "GET",
                            success: function (response) {
                                $("#tahun-aktif").html(response);
                            },
                        });
                        $("#spinner").html("")
                        table.ajax.reload();
                        Swal.fire("Good Jobs!", response.success, "success");
                    },
                });
            }
        });
    });
});
