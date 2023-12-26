$(document).ready(function () {
    // DATATABLES
    let table = $("#table-tahun-ajaran").DataTable({
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
            url: "/datatablesLaporanTahunAjaran",
            type: "GET",
        },
        order: [
            [1, "asc"]
        ],
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-tahun-ajaran").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "trimTahun",
            },
            {
                data: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            {
                targets: [1], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                targets: [2], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });
    let table_laporan = $("#table-laporan").DataTable({
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
            url: "/datatablesLaporanPresensiAll",
            type: "GET",
            data: function (a) {
                a.unique_kelas = $("#unique_kelas").val();
                a.unique_tahun_ajaran = $("#unique_tahun_ajaran").val();
                a.bulanan = $("#bulanan").val();
                a.tanggal_awal = $("#tanggal_awal").val();
                a.tanggal_akhir = $("#tanggal_akhir").val();
                a.hari_ini = $("#hari-ini").val();
            }
        },
        order: [
            [1, "asc"]
        ],
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-laporan").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nama",
            },
            {
                data: "hadir",
            },
            {
                data: "izin",
            },
            {
                data: "sakit",
            },
            {
                data: "alfa",
            },
        ],
        columnDefs: [
            {
                targets: [1], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                targets: [2], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });

    $("#bulanan").on("change", function () {
        $("#table-periode").html(`Bulan ${$("#bulanan option:selected").text()}`)
        $("#tanggal_awal").val("")
        $("#tanggal_akhir").val("")
        $("#hari-ini").val("0")
        table_laporan.ajax.reload()
    })
    $("#tanggal_awal").on("change", function () {
        $("#bulanan").val("")
        $("#hari-ini").val("0")
        if ($("#tanggal_akhir").val() != "") {
            $("#table-periode").html(`Tanggal ${formatDateIndonesia($("#tanggal_awal").val())} s.d ${formatDateIndonesia($("#tanggal_akhir").val())}`)
            table_laporan.ajax.reload()
        }
    })
    $("#tanggal_akhir").on("change", function () {
        $("#bulanan").val("")
        $("#hari-ini").val("0")
        if ($("#tanggal_awal").val() != "") {
            $("#table-periode").html(`Tanggal ${formatDateIndonesia($("#tanggal_awal").val())} s.d ${formatDateIndonesia($("#tanggal_akhir").val())}`)
            table_laporan.ajax.reload()
        }
    })
    //LAPORAN HARI INI
    $("#laporan-hari-ini").on("click", function () {
        $("#tanggal_awal").val("")
        $("#tanggal_akhir").val("")
        $("#bulanan").val("")
        $("#hari-ini").val("1")
        $("#table-periode").html(`Hari Ini`)
        table_laporan.ajax.reload();
    })

    // CEKTAK LAPORAN PDF
    $("#cetak-laporan-pdf").on("click", function () {
        if ($("#tanggal_awal").val() == "" && $("#tanggal_akhir").val() == "" && $("#bulanan").val() == "" && $("#hari-ini").val() == "0") {
            Swal.fire("Warning!", "Silahkan Pilih Periode Pelaporan!", "warning");
        } else {
            $("#modal-pdf").modal('show');
            let pdfFrame = $("#pdfFrame");
            // Load PDF using AJAX
            $.ajax({
                url: '/get-pdf',
                type: 'GET',
                dataType: 'arraybuffer',
                success: function (data) {
                    var blob = new Blob([data], { type: 'application/pdf' });
                    var pdfUrl = URL.createObjectURL(blob);
                    pdfFrame.attr('src', pdfUrl);
                }
            });
        }
    })
    // .DATATABLES

    $("#table-tahun-ajaran").on("click", ".pilih-kelas", function () {
        let unique = $(this).attr("data-unique")
        $("#title-tahun").html(`(${$(this).attr("data-tahun")})`)
        $("#table-tahun").html(`(${$(this).attr("data-tahun")})`)
        $("#unique_tahun_ajaran").val(unique)
        $.ajax({
            url: "/getKelasLaporan/" + unique,
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#render-kelas").html(response.data);
            }
        });
        $("#modal-kelas").modal("show")
    })

    // KETIKA KELAS DIPILIH
    $("#modal-kelas").on("click", "#card-kelas", function () {
        $("#tanggal_awal").val("")
        $("#tanggal_akhir").val("")
        $("#bulanan").val("")
        $("#hari-ini").val("0")
        let unique = $(this).attr('data-unique-kelas');
        $("#title-kelas-laporan").html(`KELAS ${$(this).attr('data-kelas')}`);
        $("#unique_kelas").val(unique);
        table_laporan.ajax.reload();
        // AJAX
        // ./AJAX
        $("#modal-kelas").modal("hide");
        $("#modal-laporan").modal("show");
    })

    function formatDateIndonesia(dateString) {
        let options = { year: 'numeric', month: 'long', day: 'numeric' };
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', options);
    }
})