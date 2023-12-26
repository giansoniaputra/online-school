$(document).ready(function () {
    $(".card-hover").on("click", function () {
        let nama_kelas = $(this).attr("data-kelas");
        let unique_kelas = $(this).attr("data-unique-kelas");
        let unique_perwalian = $(this).attr("data-perwalian");
        let unique_guru = $(this).attr("unique-guru");
        $("#unique_perwalian").val(unique_perwalian);
        $("#unique_kelas").val(unique_kelas);
        $("#modal-perwalian").modal("show");
        $("#kelas-wali").html(nama_kelas)
        $.ajax({
            url: "/getWali",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                let option = $("#pilih-wali")
                let element = '<option value="" selected disabled>Pilih Wali</option>';
                response.data.map((guru) => {
                    element += `<option value="${guru.unique}" ${(guru.unique == unique_guru) ? 'selected' : ''}>${guru.nama_guru}</option>`;
                })
                option.html(element)
            }
        });
    });

    $(".btn-close-perwalian").on("click", function () {
        $("#unique_perwalian").val("")
        $("#unique_kelas").val("")
        $("#kelas-wali").html("")
        $("#pilih-wali").html("")
    });

    $("#btn-perwalian").on("click", function () {
        let unique = $("#unique_perwalian").val();
        let unique_kelas = $("#unique_kelas").val();
        let unique_teacher = $("select[name='unique_teacher']").val();
        let token = $("#token").val();
        if (unique_teacher == null) {
            Swal.fire("Warning!", "Silahkan Pilih Wali", "warning");
        } else {
            $.ajax({
                data: {
                    unique: unique,
                    unique_kelas: unique_kelas,
                    unique_teacher: unique_teacher,
                    _token: token,
                },
                url: "/updateWaliKelas",
                type: "POST",
                dataType: 'json',
                success: function (response) {
                    if (response.errors) {
                        Swal.fire("Warning!", response.errors, "warning");
                    } else {
                        $("#unique_perwalian").val("")
                        $("#unique_kelas").val("")
                        $("#kelas-wali").html("")
                        $("#pilih-wali").html("")
                        $("#modal-perwalian").modal("hide");
                        let card = document.querySelectorAll(".card-hover")
                        card.forEach((a) => {
                            let kelas = a.getAttribute("data-unique-kelas")
                            if (kelas == unique_kelas) {
                                a.children[0].children[1].innerHTML = 'Wali Kelas: ' + response.guru
                            }
                        })
                        Swal.fire("Success!", response.success, "success");
                    }
                }
            });
        }
    });
});