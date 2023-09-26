$(document).ready(function () {
    let table = $("#table-setting-tagihan").DataTable({
        processing: true,
        responsive: true,
        lengthChange: false,
        paging: false,
        autoWidth: false,
        serverSide: true,
        ajax: {
            url: "/dataTablesSettingTagihan",
            type: "GET",
            data: function (d) {
                d.unique_jenis_pembayaran = $("#unique_jenis_pembayaran").val();
                d.unique_tahun_ajaran = $("#unique_tahun_ajaran").val();
            },
        },
        columns: [
            {
                data: "kelas2",
            },
            {
                data: "nominal",
                render: function (data, type, row, meta) {
                    let split = data.split("+");
                    return type === "display"
                        ? `<p class="view-nominal view-nominal-${split[1]}" data-unique="${split[1]}" data-nominal="${split[0]}">${new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        })
                            .format(split[0])
                            .replace(/\./g, ".")}</p>`
                        : data;
                },
            }
        ],
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
    });
    let select = {
        unique_jenis_pembayaran: $("#unique_jenis_pembayaran").val(),
        unique_kelas: $("#unique_kelas").val(),
        unique_tahun_ajaran: $("#unique_tahun_ajaran").val(),
    }

    $("#action-button").on("click", "#btn-setting", function () {
        $(this).attr("disabled", "true");
        for (m in select) {
            let input = $(`#${m}`);
            if (input.val() === null) {
                input.addClass('is-invalid')
            }
            input.on("change", function () {
                input.removeClass('is-invalid')
            })
        }
        $("#spinner").html(loader)
        let formdata = $('#form-setting form').serializeArray();
        let data = {}
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $('#form-setting form').serialize(),
            url: "/settingTagihan",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                $("#spinner").html("")
                $("#action-button").html("")
                table.ajax.reload();
                Swal.fire("Good job!", response.success, "success");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    })

    $("#unique_jenis_pembayaran").on("change", function () {
        $.ajax({
            data: {
                unique_jenis_pembayaran: $(this).val(),
                unique_tahun_ajaran: $("#unique_tahun_ajaran").val(),
            },
            url: "/cekDataTagihan",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                if (response.kosong) {
                    if ($("#unique_tahun_ajaran").val() != null) {
                        $("#action-button").html(`<button type="button" class="btn btn-sm mb-1 btn-info" id="btn-setting">Setting Tagihan</button>`);
                    }
                } else {
                    $("#action-button").html("")
                }
            }
        });
        table.ajax.reload()
    })
    $("#unique_tahun_ajaran").on("change", function () {
        $.ajax({
            data: {
                unique_jenis_pembayaran: $("#unique_jenis_pembayaran").val(),
                unique_tahun_ajaran: $(this).val(),
            },
            url: "/cekDataTagihan",
            type: "GET",
            dataType: 'json',
            success: function (response) {
                if (response.kosong) {
                    if ($("#unique_jenis_pembayaran").val() != null) {
                        $("#action-button").html(`<button type="button" class="btn btn-sm mb-1 btn-info" id="btn-setting">Setting Tagihan</button>`);
                    }
                } else {
                    $("#action-button").html("")
                }
            }
        });
        table.ajax.reload()
    })

    //Ketika Nominal Di Klik
    $("#table-setting-tagihan").on("click", ".view-nominal", function () {
        $("#table-setting-tagihan .view-nominal").removeClass("d-none");
        $("#table-setting-tagihan .form-nominal").remove();
        $(this).addClass("d-none");
        const unique = $(this).attr("data-unique");
        const nominal = $(this).attr("data-nominal");
        let element = $(`#table-setting-tagihan .view-nominal-${unique}`);
        element.after(`
        <form class="d-inline form-nominal" action="javascript:;">
            <input type="hidden" name="unique" value="${unique}"/>
            <input type="text" class="form-control input-autofocus money" name="nominal" style="width:20vh" value="${nominal}">
        </form>
        `)
        $("input.money").simpleMoneyFormat({
            currencySymbol: "Rp",
            decimalPlaces: 0,
            thousandsSeparator: ".",
        });
        $("#table-setting-tagihan .input-autofocus").focus();
        $("#table-setting-tagihan .form-nominal").submit(function () {
            let formdata = $('#table-setting-tagihan .form-nominal').serializeArray();
            let data = {}
            $(formdata).each(function (index, obj) {
                data[obj.name] = obj.value;
            });
            $.ajax({
                data: $('#table-setting-tagihan .form-nominal').serialize(),
                url: "/updateNominal",
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    if (response.errors) {
                        Swal.fire("Warning!", response.errors, "warning");
                    } else {
                        table.ajax.reload();
                    }
                }
            });
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