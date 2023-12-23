$(function () {
    $("#detailPayment").click(function () {
        $("#modal-detail").modal("show");
    });
});

function detail(sequence, name) {
    let order_code = $("#purchase_order_code").val();
    $.ajax({
        type: "GET",
        url: "/product_order_details/" + sequence, // Use the route function to generate the URL
        data: {
            order_code: order_code,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#modal-detail").modal("show");
            $("#content-detail").html("");
            if (response.data.length <= 0) {
                $(".modal-title").text("Hapus Data");
                $("#modal-detail form").attr(
                    "action",
                    "/detail-purchase-order/" + code
                );

                var html =
                    `<div class="col mb-2">
                                <input type="hidden" name="id" id="id" value="` +
                    code +
                    `">
                                <span style="margin-left: 10px;">Hapus Pembelian <b>` +
                    name +
                    `</b> ?<span>
                                </div>`;

                $("#modal-detail .modal-footer #n").text("Tidak");
                $("#modal-detail .modal-footer #y").show();
                $("#content-detail").append(html);
            } else {
                $(".modal-title").text("Hapus Data Detail Terlebih Dahulu");
                $("#modal-detail .modal-footer #n").text("Ok.");
                $("#modal-detail .modal-footer #y").hide();
            }
        },
        error: function (error) {
            console.log("Ajax request failed");
        },
    });
}
