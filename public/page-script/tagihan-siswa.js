$(document).ready(function () {
    $("#pilih-siswa").on("change", function () {
        $("#spinner").html(loader)
        const unique = $(this).val();
        if (unique == "") {
            $("#spinner").html("")
            $("#daftar-tagihan").html(`
                <tr>
                    <td colspan="5" class="text-center">No data available in table</td>
                </tr>
            `)
        } else {
            $.ajax({
                data: { unique: unique },
                url: "/pilihSiswa",
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    $("#nama-siswa").html(response.data.nama)
                    $("#kelas-siswa").html(response.data.kelas2)
                    $.ajax({
                        data: { unique: response.data.unique },
                        url: "/getDataTagihan",
                        success: function (hasil) {
                            $("#spinner").html("")
                            $("#daftar-tagihan").html(hasil)
                        }
                    });
                    $.ajax({
                        data: { unique: response.data.unique },
                        url: "/getDataTagihanLunas",
                        success: function (hasil) {
                            $("#spinner").html("")
                            $("#daftar-tagihan-lunas").html(hasil)
                        }
                    });
                }
            });
        }
    })
    $("input.money").simpleMoneyFormat({
        currencySymbol: "Rp",
        decimalPlaces: 0,
        thousandsSeparator: ".",
    });
    $("#table-tagihan").DataTable({
        processing: true,
        searching: true,
        bLengthChange: true,
        info: false,
        paging: false,
        ordering: true,
    });
    $("#table-tagihan-lunas").DataTable({
        processing: true,
        searching: true,
        bLengthChange: true,
        info: false,
        paging: false,
        ordering: true,
    });
    $(".btn-close").on("click", function () {
        $("#unique_student").val("");
        $("#unique_kelas").val("");
        $("#unique_tahun_ajaran").val("");
        $("#unique_jenis_pembayaran").val("");
        $("#unique_generate").val("");
        $("#periode_tagihan").val("");
        $("#tanggal_bayar").val("");
        $("#nominal").val(0);
        $("#sisa_bayar").val("")
        $("#kembali").val(0)
    })
    $("#daftar-tagihan").on("click", "#btn-bayar", function () {
        $("#spinner").html(loader);
        $("#unique_student").val($(this).attr("data-siswa"));
        $("#unique_kelas").val($(this).attr("data-kelas"));
        $("#unique_tahun_ajaran").val($(this).attr("data-unique-tahun"));
        $("#unique_jenis_pembayaran").val($(this).attr("data-unique-pembayaran"));
        $("#unique_generate").val($(this).attr("data-unique"));
        $("#periode_tagihan").val($(this).attr("data-periode"));
        $("#nama").val($("#nama-siswa").html())
        $("#kelas").val($("#kelas-siswa").html())
        $("#periode").val($(this).attr("data-periode"))
        $("#nominal_tagihan").val($(this).attr("data-nominal"))
        $("#title-modal").html(`BAYAR TAGIHAN ${$(this).attr("data-jenis")}`)
        //REQUEST AJAX
        $.ajax({
            data: {
                unique_generate: $(this).attr("data-unique"),
                periode_tagihan: $(this).attr("data-periode"),
                current_nominal: $(this).attr("data-nominal")
            },
            url: "/cekCurrentTagihan",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                if (response.data_once) {
                    let element = `
                    <div class="col-sm-12 mb-2">
                            <div class="form-group">
                            <label for="sisa_tagihan">Sisa Tagihan</label>
                                <input type="text" id="sisa_tagihan" class="form-control" value="${new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 0,
                    })
                            .format(response.data_once)
                            .replace("Rp", "")
                            .replace(/\./g, ",")}" disabled>
                            </div>
                        </div>
                    `;
                    $("#sisa_bayar").val(response.data_once)
                    $(".modal-tambahan").html(element);
                    $("#spinner").html("");
                } else if (response.data_month) {
                    $("#sisa_bayar").val($("#nominal_tagihan").val())
                    let element = `
                    <div class="col-sm-12 mb-2">
                    <div class="alert alert-success" role="alert">
                    Sisa Bayar: <strong>${response.data_month}</strong> Bulan!
                    </div>
                    </div>
                    `;
                    $(".modal-tambahan").html(element);
                    $("#spinner").html("");
                }
            }
        });
        $("#modal-bayar").modal('show')
    })
    //KETIKA NOMINAL DIKETIKAN
    $("#nominal").on("keyup", function () {
        let nominal = $(this).val().replace(/\,/g, "");
        let kembali = $("#kembali");
        let sisa_bayar = $("#sisa_bayar").val();

        let hasil = parseInt(nominal) - parseInt(sisa_bayar)
        if (hasil <= 0 || isNaN(hasil)) {
            kembali.val("0")
        } else {
            kembali.val(new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            })
                .format(hasil)
                .replace("Rp", "")
                .replace(/\./g, ","))
        }

    })

    //SIMPAN PEMBAYARAN
    $("#save-pembayaran").on('click', function () {
        $(this).attr('disabled', 'true')
        let formdata = $('#modal-bayar form').serializeArray();
        let data = {}
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $('#modal-bayar form').serialize(),
            url: "/tagihan_siswa",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                if (response.minus) {
                    $("#save-pembayaran").removeAttr("disabled");
                    Swal.fire("Warning!", response.minus, "warning");
                } else if (response.errors) {
                    $("#save-pembayaran").removeAttr("disabled");
                    displayErrors(response.errors);
                } else {
                    $("#save-pembayaran").removeAttr("disabled");
                    $("#pilih-siswa").val("").trigger("change")
                    $("#daftar-tagihan").html(`
                        <tr>
                            <td colspan="5" class="text-center">No data available in table</td>
                        </tr>
                    `)
                    $("#daftar-tagihan-lunas").html(`
                        <tr>
                            <td colspan="5" class="text-center">No data available in table</td>
                        </tr>
                    `)
                    $("#unique_student").val("");
                    $("#unique_kelas").val("");
                    $("#unique_tahun_ajaran").val("");
                    $("#unique_jenis_pembayaran").val("");
                    $("#unique_generate").val("");
                    $("#periode_tagihan").val("");
                    $("#tanggal_bayar").val("");
                    $("#nominal").val(0);
                    $("#sisa_bayar").val("")
                    $("#kembali").val(0)
                    $("#modal-bayar").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                }
            }
        });
    })


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
                if (inputElement.attr("type") == "text" || inputElement.attr("type") == "number") {
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
                } else if (inputElement.attr("type") == "password") {
                    inputElement.on("click", function () {
                        $(this).removeClass("is-invalid");
                    });
                } else if (inputElement.attr("type") == "email") {
                    inputElement.on("click", function () {
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
})

