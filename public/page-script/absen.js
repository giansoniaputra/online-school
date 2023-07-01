$(document).ready(function () {
    let table = $("#table-absen").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        serverSide: true,
        ajax: "/datatablesAbsen",
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
                data: "kelas",
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
                data: "matpel",
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
        ],
        columnDefs: [
            // {
            //     targets: [5], // index kolom atau sel yang ingin diatur
            //     className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            // },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });

    $("#btn-add-bap").on("click", function () {
        let bap = $("#bap").val();
        if (bap == "") {
            $("#bap").addClass("is-invalid");
        }
    });
    $("#bap").on("click", function () {
        $(this).removeClass("is-invalid");
    });
});
