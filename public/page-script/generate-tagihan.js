$(document).ready(function () {
    let table = $("#table-generate-tagihan").DataTable({
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
            url: "/dataTablesListTagihan",
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-generate-tagihan").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "tahun",
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
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });
    //DATA TABLES SISWA
    let table_siswa = $("#table-tagihan-siswa").DataTable({
        responsive: false,
        paging: false,
        autoWidth: false,
        serverSide: true,
        ajax: {
            url: "/dataTablesTagihanSiswaGenerate",
            type: "GET",
            data: function (d) {
                d.unique_kelas = $("#unique_kelas").val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#table-tagihan-siswa").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nama",
            },
            {
                data: "action",
                orderable: false,
                searchable: false,
            },
        ],
        order: [
            [1, "asc"]
        ],
        columnDefs: [
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
    $("#table-generate").DataTable({});

    //KETIKA TOMBOL CARI DIKLIK
    $("#table-generate-tagihan").on("click", ".generate-button", function () {
        $("#spinner").html(loader)
        $(".title-periode-tagihan").html($(this).parent().parent().children().eq(1).html())
        let unique = $(this).attr("data-unique");
        let unique_tagihan = document.querySelectorAll('#unique-tahun-ajaran');
        unique_tagihan.forEach((e) => {
            e.value = unique;
        })
        $.ajax({
            data: { unique_tahun_ajaran: unique },
            url: "/cekSettingTagihan",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                if (response.errors) {
                    $("#spinner").html("")
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: response.errors,
                        footer: '<a href="/setting_tagihan">Setting tagihan disini</a>'
                    })
                } else {
                    $("#spinner").html("")
                    $("#table-generate").parent().addClass("d-flex justify-content-center")
                    $("#table-generate").css({
                        width: "20000px"
                    })
                    $("#modal-generate-tagihan").modal("show")
                }
            }
        });
    })
    //KETIKA TOMBOL GENERATE KE SISWA DI KLIK
    $("#table-generate").on("click", "#btn-generate", function () {
        $("#spinner").html(loader)
        let form = $(this).parent().parent().children().eq(1);
        let formdata = form.serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        let jenis = formdata.map(a => a.name);
        if (!jenis[2]) {
            Swal.fire("Warning!", `Silahkan Pilih Jenis Pembayaran yang Ingin Digenerate`, "warning");
            $("#spinner").html("")
        } else {
            $.ajax({
                data: form.serialize(),
                url: "/cariSiswa",
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $("#spinner").html("")
                    $("#unique_kelas").val(response.success.unique_kelas);
                    $("#unique_tagihan").val(response.success.jenis);
                    table_siswa.ajax.reload();
                    $("#table-tagihan-siswa").parent().addClass("d-flex justify-content-center")
                    $("#table-tagihan-siswa").css({
                        width: "20000px"
                    })
                    $("#modal-tagihan-siswa").modal("show")
                    $("#modal-generate-tagihan").modal("hide")
                }
            });
        }

    })
    $(".btn-close-siswa").on("click", function () {
        $("#modal-generate-tagihan").modal("show")
    })
    let check = document.querySelector('#select-all');
    //KETIKA INGIN MEMILIH SEMUA SISWA
    $("#select-all").on("change", function () {
        let siswa = document.querySelectorAll('input[name="siswa[]"');
        siswa.forEach(e => {
            e.checked = check.checked;
        });
    })
    //RETURN NYA
    document.querySelectorAll('input[name="siswa[]"').forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            // Periksa apakah semua sub checkbox dicentang, jika ya, centang juga master checkbox
            check.checked = document.querySelectorAll('input[name="siswa[]"').every(function (subCheckbox) {
                return subCheckbox.checked;
            });
        });
    });


    //KETIK TOMBOL GENERATE DI KLIK
    $("#button-generate").on("click", function () {
        let tagihan = $("#unique_tagihan").val();
        let tahun = $("#unique-tahun-ajaran").val();
        let kelas = $("#unique_kelas").val();
        let siswa = document.querySelectorAll('input[name="siswa[]"');
        let totalSiswa = 0;
        let completedRequests = 0;

        $("#spinner").html(loader)

        // Menghitung total siswa yang checked
        siswa.forEach((e) => {
            if (e.checked) {
                totalSiswa++;
            }
        });

        siswa.forEach((e) => {
            if (e.checked) {
                generate(e.value, tagihan, kelas, tahun, () => {
                    completedRequests++;

                    // Semua permintaan selesai, sembunyikan loader
                    if (completedRequests === totalSiswa) {
                        $("#spinner").html("");
                        Swal.fire("Good!", `Tagihan Di Generate ke ${totalSiswa} Siswa`, "success");
                        $("#modal-tagihan-siswa").modal("hide")
                        $("#modal-generate-tagihan").modal("show")
                    }
                });
            }
        })

    })

    function generate(unique_siswa, unique_tagihan, unique_kelas, unique_tahun_ajaran, callback) {
        $.ajax({
            data: {
                unique_siswa: unique_siswa,
                unique_tagihan: unique_tagihan,
                unique_kelas: unique_kelas,
                unique_tahun_ajaran: unique_tahun_ajaran,
            },
            url: "/generateTagihan",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                console.log(response);
                // Panggil callback setelah permintaan AJAX selesai
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    }

})