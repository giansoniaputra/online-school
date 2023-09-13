$(document).ready(function () {
    let table = $("#table-absen-all").DataTable({
        responsive: true,
        lengthChange: false,
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
                    // console.log(response);
                    table.ajax.reload()
                }
            });
        }
    })

    $("#table-absen-all").on('click', ".hadir-siswa-button", function () {
        $("#spinner").html(loader)
        $.ajax({
            data: {
                unique: $(this).attr("data-unique"),
                tanggal_absen: $("#tanggal_absen").val(),
                unique_siswa: $(this).attr("data-unique-siswa")
            },
            url: "/hadirAll",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                table.ajax.reload();
            }
        });
    })
});