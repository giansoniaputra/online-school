$(document).ready(function () {
    let table = $("#table-roles").DataTable({
        processing: true,
        responsive: true,
        searching: true,
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
        info: false,
        ordering: true,
        serverSide: true,
        ajax: '/dataTablesRoles',
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-roles").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "role",
            },
            {
                data: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            {
                targets: [2], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
        ],
        order: [[0, "desc"]],
    });

    //Ketika Tombol Close Di klik
    $(".btn-close").on("click", function () {
        $("#title-modal").html("");
        $("#btn-action-roles").html("");
        $("#unique").val("");
        $("#method").val("");
    })


    //Ketika Tombol Tambah Di Klik
    $("#btn-add-data").on("click", function () {
        $("#title-modal").html("Tambah Role Baru");
        $("#btn-action-roles").html(`<button type="button" class="btn btn-primary" id="save-data">Tambah Data</button>`);
    })

    //ACTION SIMPAN
    $("#modal-roles").on("click", "#save-data", function () {
        $(this).attr("disabled", true);
        $("#spinner").html(loader)
        let formdata = $('#modal-roles form').serializeArray();
        let data = {}
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $('#modal-roles form').serialize(),
            url: "/roles",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                if (response.errors) {
                    $("#modal-roles #save-data").removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $("#modal-roles #save-data").removeAttr("disabled");
                    $("#spinner").html("")
                    table.ajax.reload();
                    $("#modal-roles").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(`${jqXHR.status}\n${textStatus}\n${errorThrown}`);
            }
        });
    })

    //Ambil Data Yang Akan Diedit
    $("#table-roles").on("click", ".edit-button", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        $.ajax({
            url: "/roles/" + unique + "/edit",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                $("#title-modal").html("Edit Role");
                $("#btn-action-roles").html(`<button type="button" class="btn btn-primary" id="update-data">Update Data</button>`);
                $("#unique").val(response.data.unique);
                $("#method").val("PUT");
                $("#role").val(response.data.role);
                $("#modal-roles").modal("show");

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(`${jqXHR.status}\n${textStatus}\n${errorThrown}`);
            }
        });
    })

    //EDIT SIMPAN
    $("#modal-roles").on("click", "#update-data", function () {
        $(this).attr("disabled", true);
        $("#spinner").html(loader)
        let formdata = $('#modal-roles form').serializeArray();
        let data = {}
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $('#modal-roles form').serialize(),
            url: "/roles/" + $('#unique').val(),
            type: "POST",
            dataType: 'json',
            success: function (response) {
                if (response.errors) {
                    $("#modal-roles #update-data").removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $("#modal-roles #update-data").removeAttr("disabled");
                    $("#spinner").html("")
                    table.ajax.reload();
                    $("#modal-roles").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(`${jqXHR.status}\n${textStatus}\n${errorThrown}`);
            }
        });
    })

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
});