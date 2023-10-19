$(document).ready(function () {
    //DATA TABLES SISWA
    let table_siswa = $("#table-siswa").DataTable({
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
                    var pageInfo = $("#table-siswa").DataTable().page.info();
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
    $("#list-kelas").on("click", '.card-hover', function () {
        const unique = $(this).attr("data-unique-kelas");
        $("#title-kelas").html($(this).attr("data-kelas"));
        $("#unique_kelas").val(unique);
        table_siswa.ajax.reload();
        $("#modal-siswa").modal("show");
    });
    let check = document.querySelector('#select-all');
    //ketika Ceheckbox siswa di cek
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
})