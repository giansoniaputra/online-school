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
    //Pilih Kelas
    $("#button-naik-kelas").on("click", function () {
        document.querySelector('#unique_siswa').value = "";
        let siswas = document.querySelectorAll('input[name="siswa[]"')
        let jumlah_siswa = 0;
        siswas.forEach((siswa) => {
            let unique = document.querySelector('#unique_siswa')
            if (siswa.checked) {
                unique.value += `${siswa.value}/`
                jumlah_siswa++;
            }
        })
        if (jumlah_siswa == 0) {
            Swal.fire("Warning!", "Silahkan Pilih Siswa Terlebih Dahulu!", "warning");
        } else {
            $.ajax({
                url: "/getKelas/" + $("#unique_kelas").val(),
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    let element = response.success.map((kelas) => {
                        return `
                        <div class="col-sm-4">
                            <div class="card kelas-baru" data-unique-kelas-baru="${kelas.unique}" data-kelas="${kelas.kelas}${kelas.huruf}">
                                <div class="card-body d-flex justify-conten-center flex-column align-items-center">
                                    <h3 class="card-title">${kelas.kelas}${kelas.huruf}</h3>
                                    <p class="card-text">Silahkan klik untuk memilih kelas</p>
                                </div>
                            </div>
                        </div>
                        `;
                    })
                    $("#render-kelas").html(element)
                    $("#modal-siswa").modal("hide");
                    $("#modal-kelas").modal("show");
                }
            });
        }

    })
    //Naik Kelas
    $("#render-kelas").on("click", ".kelas-baru", function () {
        let kelas = $(this).attr("data-kelas");
        let kelas_baru = $(this).attr("data-unique-kelas-baru");
        $.ajax({
            data: { siswas: $("#unique_siswa").val() },
            url: "/getSiswa",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                console.log(response.data);
                Swal.fire({
                    title: `Anda akan menaikan siswa/i berikut ke kelas ${kelas}?`,
                    html: `<small class="text-success">Silahkan scroll kebawah!</small><br>${response.data}`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Naikan!",
                    width: '80%'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            data: {
                                siswas: $("#unique_siswa").val(),
                                kelas_lama: $("#unique_kelas").val(),
                                kelas_baru: kelas_baru
                            },
                            url: "/naikKelas",
                            type: "GET",
                            dataType: 'json',
                            success: function (response) {
                                $("#unique_siswa").val();
                                $("#unique_kelas").val();
                                Swal.fire("Success!", response.success, "success");
                                $("#modal-kelas").modal("hide");
                            }
                        });
                    }
                });
            }
        });
    })


})