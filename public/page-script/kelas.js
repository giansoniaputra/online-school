$(document).ready(function () {
    let table = $("#table-kelas").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        serverSide: true,
        ajax: "/datatablesKelas",
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-kelas").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "kelas",
            },
            {
                data: "huruf",
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

    $("#btn-add-data").on("click", function () {
        $("#title-modal").html("Tambah Data Kelas");
        $("#modal-kelas #btn-action").html(
            '<button class="btn btn-primary" id="save-data">Tambah Data</button>'
        );
    });

    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#kelas").val("");
        $("#huruf").val("");
    });
    //Action Save
    $("#modal-kelas").on("click", "#save-data", function () {
        let formdata = $("#modal-kelas form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-kelas form").serialize(),
            url: "/kelas",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#unique").val("");
                    $("#method").val("");
                    $("#kelas").val("");
                    $("#huruf").val("");
                    table.ajax.reload();
                    $("#modal-kelas").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });

    //Ambil Data yang ingin di edit
    $("#table-kelas").on("click", ".edit-kelas-button", function () {
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/kelas/" + unique + "/edit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#title-modal").html("Edit Data Kelas");
                $("#modal-kelas #btn-action").html(
                    '<button class="btn btn-primary" id="update-data">Update Data</button>'
                );
                $("#unique").val(unique);
                $("#method").val("PUT");
                $("#kelas").val(response.data.kelas);
                $("#huruf").val(response.data.huruf);
                $("#modal-kelas").modal("show");
            },
        });
    });
    //Action Update
    $("#modal-kelas").on("click", "#update-data", function () {
        let formdata = $("#modal-kelas form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-kelas form").serialize(),
            url: "/kelas/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#unique").val("");
                    $("#method").val("");
                    $("#kelas").val("");
                    $("#huruf").val("");
                    table.ajax.reload();
                    $("#modal-kelas").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });

    //HAPUS DATA KELAS
    //HAPUS DATA
    $("#table-kelas").on("click", ".delete-kelas-button", function () {
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
                        unique: unique,
                    },
                    url: "/kelas/" + unique,
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
});
