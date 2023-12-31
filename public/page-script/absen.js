$(document).ready(function () {
    let table = $("#table-absen").DataTable({
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
        processing: true,
        autoWidth: false,
        serverSide: true,
        paging: false,
        ajax: {
            url: "/datatablesAbsen",
            type: "GET",
            data: function (d) {
                d.unique = $("#bap_unique_now").val();
            },
        },
        order: [
            [1, "asc"]
        ],
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-absen").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nama",
            },
            {
                data: "kelas2",
            },
            {
                data: "kehadiran",
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
    let table2 = $("#table-bap").DataTable({
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
            url: "/datatablesBAP",
            type: "GET",
            data: function (d) {
                d.tahun_ajaran = $("#tahun_ajaran").val();
                d.f_matpel = $("#filter-matpels").val();
            },
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-bap").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nama_matpel",
            },
            {
                data: "kelas_siswa",
            },
            {
                data: "pertemuan",
            },
            {
                data: "tahun_aktif",
            },
            {
                data: "tanggal_bap",
            },
            {
                data: "bap",
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
    //FILTER MATA PELAJARAN
    $("#filter-matpels").on("change", function () {
        table2.ajax.reload();
    });
    //RESET FROM
    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#pertemuan").val("");
        $("#matpel_unique").val("");
        $("#bap").val("");
        $("#modal-bap #kelas_echo").remove();
    });
    $("#btn-add-bap").on("click", function () {
        let bap = $("#bap_per").val();
        if (bap == "" || bap <= 0) {
            $("#bap_per").addClass("is-invalid");
        } else {
            $("#pertemuan").val(bap);
            $("#tahun_ajaran_unique").val($("#tahun_ajaran").val());
            $("#btn-action-bap").html(
                '<button type="button" class="btn btn-primary" id="btn-save-data">Tambah BAP</button>'
            );
            $("#bap_per").removeClass("is-invalid");
            $("#modal-bap").modal("show");
        }
    });
    $("#bap").on("click", function () {
        $(this).removeClass("is-invalid");
    });
    //Ketika tahun ajaran di pilih
    $("#tahun_ajaran").on("change", function () {
        table2.ajax.reload();
    });
    //ACTION SAVE BAP
    $("#modal-bap").on("click", "#btn-save-data", function () {
        $(this).attr("disabled", "true");
        $("#spinner").html(loader)
        let formdata = $("#modal-bap form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-bap form").serialize(),
            url: "/absen",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    $(this).removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $(this).removeAttr("disabled");
                    $("#spinner").html("")
                    $("#modal-bap #kelas_echo").remove();
                    $("#unique").val("");
                    $("#method").val("");
                    $("#pertemuan").val("");
                    $("#matpel_unique").val("");
                    $("#bap").val("");
                    $("#modal-bap").modal("hide");
                    table2.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });
    $("#matpel_unique").on("change", function () {
        $("#spinner").html(loader)
        $("#modal-bap #kelas_echo").remove();
        let matpel = $(this).val();
        let parent = $(this).parent().parent().parent();
        let pertemuan = $("#pertemuan").val();
        let kelas = $("#modal-bap #kelas").val();
        let tahun_ajaran = $("#tahun_ajaran_unique").val();
        $.ajax({
            data: {
                matpel: matpel,
                tahun_ajaran: tahun_ajaran,
                pertemuan: pertemuan,
                kelas: kelas,
            },
            url: "/getCurrentBAP",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    if (matpel == "") {
                        $("#spinner").html("")
                        $("#modal-bap #kelas_echo").remove();
                    } else {
                        $.ajax({
                            data: {
                                matpel: matpel,
                            },
                            url: "/getAllClass",
                            success: function (response) {
                                $("#spinner").html("")
                                parent.after(response);
                            },
                        });
                    }
                    $("#unique").val(response.success.unique);
                    $("#bap").val(response.success.bap);
                    $("#btn-action-bap").html(
                        '<button type="button" class="btn btn-warning" id="btn-update-data">Update BAP</button>' +
                        '<button type="button" class="btn btn-danger" id="btn-delete-data">Hapus BAP</button>'
                    );
                } else {
                    if (matpel == "") {
                        $("#spinner").html("")
                        $("#modal-bap #kelas_echo").remove();
                    } else {
                        $.ajax({
                            data: {
                                matpel: matpel,
                            },
                            url: "/getAllClass",
                            success: function (response) {
                                $("#spinner").html("")
                                parent.after(response);
                            },
                        });
                    }
                    $("#unique").val("");
                    $("#method").val("");
                    $("#bap").val("");
                    $("#btn-action-bap").html(
                        '<button type="button" class="btn btn-primary" id="btn-save-data">Tambah BAP</button>'
                    );
                }
            },
        });
    });
    $("#modal-bap").on("change", "#kelas", function () {
        let matpel = $("#matpel_unique").val();
        let pertemuan = $("#pertemuan").val();
        let kelas = $(this).val();
        let tahun_ajaran = $("#tahun_ajaran_unique").val();
        $.ajax({
            data: {
                matpel: matpel,
                tahun_ajaran: tahun_ajaran,
                pertemuan: pertemuan,
                kelas: kelas,
            },
            url: "/getCurrentBAP",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#unique").val(response.success.unique);
                    $("#bap").val(response.success.bap);
                    $("#btn-action-bap").html(
                        '<button type="button" class="btn btn-warning" id="btn-update-data">Update BAP</button>' +
                        '<button type="button" class="btn btn-danger" id="btn-delete-data">Hapus BAP</button>'
                    );
                } else {
                    $("#unique").val("");
                    $("#method").val("");
                    $("#bap").val("");
                    $("#btn-action-bap").html(
                        '<button type="button" class="btn btn-primary" id="btn-save-data">Tambah BAP</button>'
                    );
                }
            },
        });
    });
    // $("#tahun_ajaran_unique").on("change", function () {
    //     let matpel = $("#matpel_unique").val();
    //     let pertemuan = $("#pertemuan").val();
    //     let tahun_ajaran = $(this).val();
    //     $.ajax({
    //         data: {
    //             matpel: matpel,
    //             tahun_ajaran: tahun_ajaran,
    //             pertemuan: pertemuan,
    //         },
    //         url: "/getCurrentBAP",
    //         type: "GET",
    //         dataType: "json",
    //         success: function (response) {
    //             if (response.success) {
    //                 $("#unique").val(response.success.unique);
    //                 $("#bap").val(response.success.bap);
    //                 $("#btn-action-bap").html(
    //                     '<button type="button" class="btn btn-warning" id="btn-update-data">Update BAP</button>' +
    //                         '<button type="button" class="btn btn-danger" id="btn-delete-data">Hapus BAP</button>'
    //                 );
    //             } else {
    //                 $("#unique").val("");
    //                 $("#method").val("");
    //                 $("#bap").val("");
    //                 $("#btn-action-bap").html(
    //                     '<button type="button" class="btn btn-primary" id="btn-save-data">Tambah BAP</button>'
    //                 );
    //             }
    //         },
    //     });
    // });
    //EDIT BAP
    $("#modal-bap").on("click", "#btn-update-data", function () {
        $(this).attr("disabled", "true");
        $("#spinner").html(loader)
        $("#method").val("PUT");
        let formdata = $("#modal-bap form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-bap form").serialize(),
            url: "/absen/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    $(this).removeAttr("disabled");
                    $("#spinner").html("")
                    displayErrors(response.errors);
                } else {
                    $(this).removeAttr("disabled");
                    $("#spinner").html("")
                    $("#modal-bap #kelas_echo").remove();
                    $("#unique").val("");
                    $("#method").val("");
                    $("#pertemuan").val("");
                    $("#matpel_unique").val("");
                    $("#bap").val("");
                    $("#modal-bap").modal("hide");
                    table2.ajax.reload();
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });
    //HAPUS BAP
    $("#modal-bap").on("click", "#btn-delete-data", function () {
        $("#method").val("DELETE");
        Swal.fire({
            title: "Apakah Kamu Yakin?",
            text: "Kamu akan menghapus data BAP!",
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
                        unique: $("#unique").val(),
                    },
                    url: "/deletBAP",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $("#spinner").html("")
                        $("#unique").val("");
                        $("#method").val("");
                        $("#pertemuan").val("");
                        $("#matpel_unique").val("");
                        $("#bap").val("");
                        $("#modal-bap").modal("hide");
                        table2.ajax.reload();
                        Swal.fire("Good job!", response.success, "success");
                    },
                });
            }
        });
    });
    //Mulai Absen
    $("#table-bap").on("click", ".absen-button", function () {
        $("#basictab1").removeClass("active");
        $("#basictab2").addClass("active");
        $("#tab1").removeClass("show active");
        $("#tab2").addClass("show active");
        let unique_bap = $(this).attr("data-unique");
        let tahun_ajaran = $("#input-tahun-ajaran-aktif").val();
        let kelas = $(this).attr("data-kelas");
        $("#matpel-judul").html($(this).attr("data-matpel"));
        $("#tahun-ajaran-judul").html($(this).attr("data-tahun-ajaran"));
        $("#tanggal-absen-judul").html($(this).attr("data-tanggal-bap"));
        $.ajax({
            data: {
                unique_bap: unique_bap,
                tahun_ajaran: tahun_ajaran,
                kelas: kelas,
            },
            url: "/inputAbsen",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#bap_unique_now").val(response.data.bap_unique);
                table.ajax.reload();
            },
        });
    });

    //JIKA HADIR
    $("#table-absen").on("click", ".hadir-siswa-button", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        let parent = $(this).parent().parent();
        parent.children().eq(3).html("Loading...");
        $.ajax({
            data: { unique: unique },
            url: "/absenHadir",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            },
        });
    });
    //JIKA SAKIT
    $("#table-absen").on("click", ".sakit-siswa-button", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        let parent = $(this).parent().parent();
        parent.children().eq(3).html("Loading...");
        $.ajax({
            data: { unique: unique },
            url: "/absenSakit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            },
        });
    });
    //JIKA IZIN
    $("#table-absen").on("click", ".izin-siswa-button", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        let parent = $(this).parent().parent();
        parent.children().eq(3).html("Loading...");
        $.ajax({
            data: { unique: unique },
            url: "/absenIzin",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            },
        });
    });
    //JIKA ALFA
    $("#table-absen").on("click", ".alfa-siswa-button", function () {
        $("#spinner").html(loader)
        let unique = $(this).attr("data-unique");
        let parent = $(this).parent().parent();
        parent.children().eq(3).html("Loading...");
        $.ajax({
            data: { unique: unique },
            url: "/absenAlfa",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
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
