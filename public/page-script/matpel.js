$(document).ready(function () {
    let table = $("#table-matpel").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        serverSide: true,
        ajax: "/datatablesMatpel",
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-matpel").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nama_matpel",
            },
            {
                data: "kelas",
            },
            {
                data: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            {
                targets: [3], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });
    //Ketika Button Tambah Data di klik
    $("#btn-add-data").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#title-modal").html("<p>Tambah Data Mata Pelajaran</p>");
        $("#btn-action").html(
            '<button type="button" class="btn btn-primary" id="btn-save-data">Simpan</button>'
        );
    });

    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#title-modal").html("<p>Data Siswa</p>");
        $("#btn-action").html("");
        //reset form
        $("#nama_matpel").val("");
        $("#kelas").val("");
    });

    $("#modal-matpel").on("click", "#btn-save-data", function () {
        let formdata = $("#modal-matpel form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-matpel form").serialize(),
            url: "/matpel",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#modal-matpel").modal("hide");
                    //reset form
                    $("#nama_matpel").val("");
                    $("#kelas").val("");
                    $("#unique").val("");
                    $("#method").val("");
                    table.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });
    //AMBIL DATA MATA PELJARAN YANG AKAN DI EDIT
    $("#table-matpel").on("click", ".edit-matpel-button", function () {
        let unique = $(this).attr("data-unique");
        $("#modal-matpel").modal("show");
        $.ajax({
            data: { unique: unique },
            url: "/matpel/" + unique + "/edit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#nama_matpel").val(response.data.nama_matpel);
                $("#kelas").val(response.data.kelas);
                $("#unique").val(unique);
                $("#method").val("PUT");
                $("#title-modal").html("<p>Edit Data Mata Pelajaran</p>");
                $("#btn-action").html(
                    '<button type="button" class="btn btn-primary" id="btn-update-data">Update</button>'
                );
            },
        });
    });

    // ACTION UPDATE DAATA MATA PELAJARAN
    $("#modal-matpel").on("click", "#btn-update-data", function () {
        let formdata = $("#modal-matpel form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-matpel form").serialize(),
            url: "/matpel/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#modal-matpel").modal("hide");
                    //reset form
                    $("#nama_matpel").val("");
                    $("#kelas").val("");
                    $("#unique").val("");
                    $("#method").val("");
                    table.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });
    //HAPUS DATA SISWA
    $("#table-matpel").on("click", ".hapus-matpel-button", function () {
        let unique = $(this).attr("data-unique");
        let token = $(this).attr("data-token");
        Swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Kamu akan menghapus data mata pelajaran!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    data: {
                        _method: "DELETE",
                        _token: token,
                    },
                    url: "/matpel/" + unique,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        table.ajax.reload();
                        Swal.fire("Deleted!", response.success, "success");
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
