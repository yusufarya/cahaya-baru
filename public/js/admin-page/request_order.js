function process(code, delivery_status) {
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/check-payment",
        data: { code, code },
        success: function (response) {
            if (response > 0) {
                $("#modal-proses").modal("show");

                $("#delivery").val(delivery_status).change();
                $("#code").val(code);
            } else {
                $("#modal-failed").modal("show");
            }
        },
    });
}
