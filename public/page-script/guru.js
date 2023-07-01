$(document).ready(function () {
    let table = $("#table-guru").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        serverSide: true,
        ajax: "/datatablesGuru",
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-guru").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "npk",
            },
            {
                data: "nama_guru",
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
        $("#title-modal").html("<p>Tambah Data Guru</p>");
        $("#btn-action").html(
            '<button type="button" class="btn btn-primary" id="btn-save-data">Simpan</button>'
        );
    });

    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#title-modal").html("<p>Data Guru</p>");
        $("#btn-action").html("");
        //reset form
        $("#npk").val("");
        $("#nama_guru").val("");
        $("#alamat").val("");
        $("#telepon").val("");
    });
    //ACTION SIMPAN
    $("#modal-guru").on("click", "#btn-save-data", function () {
        let formdata = $("#modal-guru form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-guru form").serialize(),
            url: "/teacher",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#unique").val("");
                    $("#method").val("");
                    $("#title-modal").html("<p>Data Guru</p>");
                    $("#btn-action").html("");
                    //reset form
                    $("#npk").val("");
                    $("#nama_guru").val("");
                    $("#alamat").val("");
                    $("#telepon").val("");
                    $("#modal-guru").modal("hide");
                    table.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });
    //AMBIL DATA GURU YANG AKAN DIEDIT
    $("#table-guru").on("click", ".edit-guru-button", function () {
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/teacher/" + unique + "/edit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#unique").val(unique);
                $("#method").val("PUT");
                $("#title-modal").html("<p>Edit Data Guru</p>");
                $("#btn-action").html(
                    '<button type="button" class="btn btn-primary" id="btn-update-data">Update</button>'
                );

                $("#npk").val(response.data.npk);
                $("#nama_guru").val(response.data.nama_guru);
                $("#alamat").val(response.data.alamat);
                $("#telepon").val(response.data.telepon);
                $("#modal-guru").modal("show");
            },
        });
    });
    //ACTION UPDATE
    $("#modal-guru").on("click", "#btn-update-data", function () {
        let formdata = $("#modal-guru form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-guru form").serialize(),
            url: "/teacher/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#unique").val("");
                    $("#method").val("");
                    $("#title-modal").html("<p>Data Guru</p>");
                    $("#btn-action").html("");
                    //reset form
                    $("#npk").val("");
                    $("#nama_guru").val("");
                    $("#alamat").val("");
                    $("#telepon").val("");
                    $("#modal-guru").modal("hide");
                    table.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });
    //HAPUS DATA GURU
    $("#table-guru").on("click", ".hapus-guru-button", function () {
        let unique = $(this).attr("data-unique");
        let token = $(this).attr("data-token");
        Swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Kamu akan menghapus data guru!",
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
                    url: "/teacher/" + unique,
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
    //POP UP MODAL AMPU
    $("#table-guru").on("click", ".ampu-guru-button", function () {
        let unique_guru = $(this).attr("data-unique");
        $("#unique-guru").val(unique_guru);
        $("#modal-ampu").modal("show");
        $.ajax({
            data: { unique: unique_guru },
            url: "/refresh_ampuan",
            success: function (response) {
                $("#daftar-ampuan").html(response);
            },
        });
    });

    $(".btn-close-ampu").on("click", function () {
        $("#unique-guru").val("");
    });

    $("#modal-ampu").on("click", ".ampu-button", function () {
        let unique_matpel = $(this).attr("unique-matpel");
        let unique_guru = $("#unique-guru").val();
        $.ajax({
            data: {
                unique_matpel: unique_matpel,
                unique_guru: unique_guru,
            },
            url: "/tambah_ampuan",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $.ajax({
                    data: { unique: unique_guru },
                    url: "/refresh_ampuan",
                    success: function (response2) {
                        $("#daftar-ampuan").html(response2);
                    },
                });
            },
        });
    });

    //HAPUS AMPUAN
    $("#modal-ampu").on("click", ".lepas-button", function () {
        let unique_matpel = $(this).attr("unique-matpel");
        let unique_guru = $("#unique-guru").val();
        $.ajax({
            data: {
                unique_matpel: unique_matpel,
                unique_guru: unique_guru,
            },
            url: "/hapus_ampuan",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $.ajax({
                    data: { unique: unique_guru },
                    url: "/refresh_ampuan",
                    success: function (response2) {
                        $("#daftar-ampuan").html(response2);
                    },
                });
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
