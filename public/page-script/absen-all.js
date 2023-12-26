$(document).ready(function () {
    let table = $("#table-absen-all").DataTable({
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
            url: "/datatablesAbsenAll",
            type: "GET",
            data: function (d) {
                d.kelas = $("#kelas").val();
                d.tanggal_absen = $("#tanggal_absen").val();
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
                    var pageInfo = $("#table-absen-all").DataTable().page.info();
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
                data: "tanggal_absen",
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

    $("#btn-add-absen").on('click', function () {
        $("#spinner").html(loader)
        if ($("#kelas").val() == "" || $("#tanggal_absen").val() == "") {
            $("#kelas").addClass('is-invalid');
            $("#tanggal_absen").addClass('is-invalid');
            $("#spinner").html("")
        } else {
            $.ajax({
                data: {
                    kelas: $("#kelas").val(),
                    tahun_ajaran: $("#input-tahun-ajaran-aktif").val(),
                    tanggal_absen: $("#tanggal_absen").val()
                },
                url: "/inputAbsenAll",
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $("#spinner").html("")
                    $("#hadir-semua").html(`<button class="btn btn-primary btn-sm" id="btn-absen-all" data-kelas="${response.data.student_kelas}" data-tanggal="${response.data.tanggal_absen}">Hadir</button>`)
                    // console.log(response);
                    table.ajax.reload()
                }
            });
        }
    })

    //HADIR SEMUA
    $("#table-absen-all").on("click", "#btn-absen-all", function () {
        $("#spinner").html(loader)
        $.ajax({
            data: {
                kelas: $(this).attr("data-kelas"),
                tanggal: $(this).attr("data-tanggal")
            },
            url: "/hadirSemua",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            }
        });
    })
    //Jika Siswa Hadir
    $("#table-absen-all").on('click', ".hadir-siswa-button", function () {
        $("#spinner").html(loader)
        $.ajax({
            data: {
                unique: $(this).attr("data-unique"),
                tanggal_absen: $("#tanggal_absen").val(),
                student_unique: $(this).attr("data-unique-siswa")
            },
            url: "/hadirAll",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            },
            error: function (a, b, c) {
                console.log(a + '\n' + b + '\n' + c);
            }
        });
    })
    //Jika Siswa Sakit
    $("#table-absen-all").on('click', ".sakit-siswa-button", function () {
        $("#spinner").html(loader)
        $.ajax({
            data: {
                unique: $(this).attr("data-unique"),
                tanggal_absen: $("#tanggal_absen").val(),
                student_unique: $(this).attr("data-unique-siswa")
            },
            url: "/sakitAll",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            }
        });
    })
    //Jika Siswa Izin
    $("#table-absen-all").on('click', ".izin-siswa-button", function () {
        $("#spinner").html(loader)
        $.ajax({
            data: {
                unique: $(this).attr("data-unique"),
                tanggal_absen: $("#tanggal_absen").val(),
                student_unique: $(this).attr("data-unique-siswa")
            },
            url: "/izinAll",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            }
        });
    })
    //Jika Siswa Alfa
    $("#table-absen-all").on('click', ".alfa-siswa-button", function () {
        $("#spinner").html(loader)
        $.ajax({
            data: {
                unique: $(this).attr("data-unique"),
                tanggal_absen: $("#tanggal_absen").val(),
                student_unique: $(this).attr("data-unique-siswa")
            },
            url: "/alfaAll",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            }
        });
    })
});