$(function () {
    var qty = $("#qty_dt").val();
    let price = $("#price").val();
    let charge = $("#charge").val();

    var totals = qty * price;
    totals += parseFloat(charge);

    $("#netto").val(replaceRupiah(totals + ".00"));
});
function changeQty() {
    var qty = $("#qty_dt").val();
    if (qty <= 1) {
        $("#qty_dt").val(1);
        var qty = $("#qty_dt").val();
    }

    let price = $("#price").val();
    let charge = $("#charge").val();

    var totals = qty * price;
    var total = replaceRupiah(totals) + ".00";

    $("#total_price").val(total);
    totals += parseFloat(charge);
    // console.log(totals);
    $("#netto").val(replaceRupiah(totals + ".00"));
}

function payOrder(id) {
    var route = "/pay-order/" + id;

    $.ajax({
        type: "POST",
        url: route,
        data: {
            id: id,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // Handle the response here
            setTimeout(() => {
                location.href = "/";
            }, 100);
        },
        error: function (error) {
            console.log("Ajax request failed");
        },
    });
}

function cancelOrder(id) {
    $("#cancelOrder").modal("show");

    $("#Y").click(function () {
        var route = "/cancel-order";

        $.ajax({
            type: "DELETE",
            url: route,
            data: {
                id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // Handle the response here
                setTimeout(() => {
                    location.href = "/";
                }, 100);
            },
            error: function (error) {
                console.log("Ajax request failed");
            },
        });
    });
}
