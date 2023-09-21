$(document).ready(function () {
    let table = $("#table-jenis-pembayaran").DataTable({
        // pagination: true,
        responsive: true,
        responsive: !0,
        language: {
            paginate: {
                previous: "<i class='ri-arrow-left-s-line'>",
                next: "<i class='ri-arrow-right-s-line'>",
            },
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass(
                "pagination-rounded"
            );
        },
        autoWidth: false,
        serverSide: true,
        ajax: {
            url: "/dataTablesJenisPembayaran",
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-jenis-pembayaran").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "jenis_pembayaran",
            },
            {
                data: "periode",
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

    //Ketika Button Tambah Di Klik
    $("#btn-add-data").on("click", function () {
        $("#title-modal").html("Tambah Jenis Pembayaran");
        $("#btn-action").html(`
        <button type="button" class="btn btn-primary" id="save-data">Simpan</button>
        `)
    });

    //KETIKA TOMBOL 
    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#jenis_pembayaran").val("");
        $("#periode").val("");
        $("#title-modal").html("");
        $("#btn-action").html("");
    });

    //ACTION SIMPAN
    $("#modal-jenis-pembayaran").on("click", "#save-data", function () {
        $("#spinner").html(loader);
        let formdata = $('#modal-jenis-pembayaran form').serializeArray();
        let data = {}
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $('#modal-jenis-pembayaran form').serialize(),
            url: "/jenis_pembayaran",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                if (response.errors) {
                    $("#spinner").html("");
                    displayErrors(response.errors);
                } else {
                    $("#spinner").html("");
                    $("#unique").val("");
                    $("#method").val("");
                    $("#jenis_pembayaran").val("");
                    $("#periode").val("");
                    $("#title-modal").html("");
                    $("#btn-action").html("");
                    table.ajax.reload();
                    $("#modal-jenis-pembayaran").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            }
        });
    })

    //AMBIL DATA YANG AKAN DI EDIT
    $("#table-jenis-pembayaran").on("click", ".edit-jenis-button", function () {
        $("#spinner").html(loader);
        const unique = $(this).attr("data-unique");
        $.ajax({
            url: `/jenis_pembayaran/${unique}/edit`,
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#unique").val(response.data.unique);
                $("#method").val("PUT");
                $("#jenis_pembayaran").val(response.data.jenis_pembayaran);
                $("#periode").val(response.data.periode);
                $("#title-modal").html("Edit Jenis Pembayaran");
                $("#btn-action").html(`
                    <button type="button" class="btn btn-primary" id="update-data">Update</button>
                    `)
                $("#modal-jenis-pembayaran").modal("show");
                $("#spinner").html("");
            }
        });
    })

    //ACTION UPDATE DATA
    $("#modal-jenis-pembayaran").on("click", "#update-data", function () {
        $("#spinner").html(loader);
        let formdata = $('#modal-jenis-pembayaran form').serializeArray();
        let data = {}
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $('#modal-jenis-pembayaran form').serialize(),
            url: "/jenis_pembayaran/" + $("#unique").val(),
            type: "POST",
            dataType: 'json',
            success: function (response) {
                if (response.errors) {
                    $("#spinner").html("");
                    displayErrors(response.errors);
                } else {
                    $("#spinner").html("");
                    $("#unique").val("");
                    $("#method").val("");
                    $("#jenis_pembayaran").val("");
                    $("#periode").val("");
                    $("#title-modal").html("");
                    $("#btn-action").html("");
                    table.ajax.reload();
                    $("#modal-jenis-pembayaran").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            }
        });
    })

    //HAPUS DATA
    $("#table-jenis-pembayaran").on("click", ".hapus-jenis-button", function () {
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
                    url: "/jenis_pembayaran/" + unique,
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
                if (inputElement.attr("type") == "text" || inputElement.attr("type") == "number") {
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
                } else if (inputElement.attr("type") == "password") {
                    inputElement.on("click", function () {
                        $(this).removeClass("is-invalid");
                    });
                } else if (inputElement.attr("type") == "email") {
                    inputElement.on("click", function () {
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
})