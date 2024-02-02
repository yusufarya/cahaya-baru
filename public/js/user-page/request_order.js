$(function () {
    const image = document.getElementById("image");
    let blah = document.getElementById("blah");
    image.onchange = (evt) => {
        const [file] = image.files;
        if (file) {
            blah.src = URL.createObjectURL(file);
        }
    };

    $("#submit").on("click", () => {
        if (!$("#size_id").val()) {
            alert("Ukuran belum dipilih");
        } else if ($("#qty").val() <= 0) {
            alert("Quantity masih 0");
        } else if (!$("#description").val()) {
            alert("Catatan masih kosong");
        } else if (!$("#image").val()) {
            alert("Gambar masih kosong");
        } else {
            $("#account_number").html("");
            $("#submit-order").modal("show");

            $("#Y").on("click", function () {
                var thisVal = $("#payment_method").val();

                var val = thisVal ? thisVal.split("-") : "";
                if (val) {
                    updatePaymentMethod(val[0]);
                    var html =
                        `<span class="alert alert-danger py-1">` +
                        val[2] +
                        `</span>`;
                    $("#account_number").append(html);
                    $("#bank_name").text(val[1]);
                }
            });
        }
    });

    $("#btnSend").on("click", function (event) {
        event.preventDefault();
        let order_code = $("#code").val();
        let dataPost = new FormData();
        let images = $("#imagePay")[0];

        dataPost.append("images", images.files[0]);
        dataPost.append("order_code", order_code);

        $.ajax({
            type: "POST",
            url: "/uploadImgDp", // Use the route function to generate the URL
            // url: "{{ route('/uploadImgPayment') }}", // Use the route function to generate the URL
            data: dataPost,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // console.log(response);
                if (response.status == "success") {
                    alert("Upload bukti pembayaran berhasil.");
                    setTimeout(() => {
                        $("#xxx").click();
                        // $("#form-custom-request").submit();
                    }, 2000);
                }
            },
            error: function (error) {
                console.log("Ajax request failed");
            },
        });
    });
});

function updatePaymentMethod(pay_method) {
    var order_code = $("#code").val();

    $.ajax({
        type: "POST",
        url: "/dpPayment", // Use the route function to generate the URL
        data: {
            order_code: order_code,
            payment_method: pay_method,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log(response);
            if (response.success) {
                $("#submit-order").modal("hide");
                $("#pay-order").modal("show");
            } else {
                alert("Proses gagal, hubungi administrator");
            }
        },
        error: function (error) {
            console.log("Ajax request failed");
        },
    });
}
