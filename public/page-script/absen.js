$(document).ready(function () {
    let table = $("#table-absen").DataTable({
        responsive: true,
        lengthChange: false,
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
        lengthChange: false,
        processing: true,
        autoWidth: false,
        serverSide: true,
        ajax: "/datatablesBAP",
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
                data: "pertemuan",
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
    //RESET FROM
    $(".btn-close").on("click", function () {
        $("#unique").val("");
        $("#method").val("");
        $("#pertemuan").val("");
        $("#matpel_unique").val("");
        $("#bap").val("");
    });
    $("#btn-add-bap").on("click", function () {
        let bap = $("#bap_per").val();
        if (bap == "" || bap <= 0) {
            $("#bap_per").addClass("is-invalid");
        } else {
            $("#pertemuan").val(bap);
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
    //ACTION SAVE BAP
    $("#modal-bap").on("click", "#btn-save-data", function () {
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
                    displayErrors(response.errors);
                } else {
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
        let matpel = $(this).val();
        let pertemuan = $("#pertemuan").val();
        $.ajax({
            data: {
                matpel: matpel,
                pertemuan: pertemuan,
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
    //EDIT BAP
    $("#modal-bap").on("click", "#btn-update-data", function () {
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
                    displayErrors(response.errors);
                } else {
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
                $.ajax({
                    data: {
                        unique: $("#unique").val(),
                    },
                    url: "/deletBAP",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
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
        $("#tab1-tab").removeClass("active");
        $("#tab2-tab").addClass("active");
        $("#tab1").removeClass("show active");
        $("#tab2").addClass("show active");
        let unique_bap = $(this).attr("data-unique");
        let tahun_ajaran = $("#input-tahun-ajaran-aktif").val();

        $.ajax({
            data: {
                unique_bap: unique_bap,
                tahun_ajaran: tahun_ajaran,
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
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/absenHadir",
            type: "GET",
            dataType: "json",
            success: function (response) {
                table.ajax.reload();
            },
        });
    });
    //JIKA SAKIT
    $("#table-absen").on("click", ".sakit-siswa-button", function () {
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/absenSakit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                table.ajax.reload();
            },
        });
    });
    //JIKA IZIN
    $("#table-absen").on("click", ".izin-siswa-button", function () {
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/absenIzin",
            type: "GET",
            dataType: "json",
            success: function (response) {
                table.ajax.reload();
            },
        });
    });
    //JIKA ALFA
    $("#table-absen").on("click", ".alfa-siswa-button", function () {
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/absenAlfa",
            type: "GET",
            dataType: "json",
            success: function (response) {
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
