$(async function () {
    $("#detail-list").hide();

    var no = 1;
    load_data_detail($("#purchase_order_id").val());

    async function getAllProduct() {
        const headers = new Headers({
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        });

        const dataFechh = await fetch("/getProducts", {
            method: "GET",
            headers: headers,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then((response) => {
                return response.data;
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });

        return dataFechh;
    }

    const allProducts = await getAllProduct();

    $("#addDetail").on("click", () => {
        let vendor_code = $("#vendor_code").val();
        let date = $("#date").val();

        if (!vendor_code) {
            alert("Vendor masih kosong");
        }

        if (vendor_code) {
            $.ajax({
                type: "POST",
                url: "/purchase-order/addDetail", // Use the route function to generate the URL
                data: {
                    vendor_code: vendor_code,
                    date: date,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    $("#addDetail").hide(100);
                    const no_trans = get_ymd(date);
                    // console.log(no_trans + response.dataId);
                    $("#vendor_code").prop("disabled", true);
                    $("#codeTrans").val(no_trans + response.dataId);
                    $("#purchase_order_id").val(response.dataId);
                    $("#detail-list").show(100);
                    // Handle the response here
                },
                error: function (error) {
                    console.log("Ajax request failed");
                },
            });
        }
    });

    $("#add-new").click(() => {
        //
        console.log("add new click");
        $("#add-new").prop("disabled", true);
        const otpAllProducts = allProducts.map((item, index) => {
            console.log(item);
            var options =
                `<option value="` +
                item.id +
                `">
                    » &nbsp; ` +
                item.name +
                ` / ` +
                item.categories.name +
                ` / ` +
                item.sizes.initial +
                `
                </option>`;

            return options;
        });

        console.log(no);
        var sequence = no++;

        var htmlrow =
            `<tr>
                <td><input type="text" name="no" id="no" class="border-0" value="` +
            sequence +
            `"></td>
                <td>
                    <select class="form-control" name="product_id" id="product_id">
                    <option value="">Pilih Produk</option> ` +
            otpAllProducts +
            `
                    </select>
                </td>
                <td><input type="text" name="qty_dt" id="qty_dt" class="form-control" placeholder="0" onkeyup="onlyNumbers(this);" style="text-align: right;"></td>
                <td><input type="text" name="price_dt" id="price_dt" class="form-control" placeholder="0" onkeyup="formatRupiah(this, this.value);" style="text-align: right;"></td>
                <td style="text-align: center">
                    <button type="button" id="add" class="btn btn-add pt-1"> <i class="fas fa-plus-square"></i> </button> |
                    <button type="button" id="cancel" class="btn text-danger p-0"> <i class="fas fa-times-circle"></i> </button>
                </td>
            </tr>`;

        $("#container-table tbody").append(htmlrow);

        // $("#cancel").on("click", () => {
        //     //
        //     console.log($(this).closest("tr"));
        //     no - 1;
        //     $(this).closest("tr").remove();
        //     // var table = document.getElementById("container-table");
        //     // console.log(table);
        // });

        $("#add").on("click", () => {
            // alert("p");
            let sequence = $("#no").val();
            let product_id = $("#product_id").val();
            let date = $("#date").val();
            let qty_dt = $("#qty_dt").val();
            let price_dt = $("#price_dt").val();
            let purchase_order_id = $("#purchase_order_id").val();

            if (!product_id) {
                alert("Produk masih kosong");
            }

            if (product_id) {
                $.ajax({
                    type: "POST",
                    url: "/purchase-order_detail/add", // Use the route function to generate the URL
                    data: {
                        product_id: product_id,
                        sequence: sequence,
                        date: date,
                        qty_dt: qty_dt,
                        price_dt: price_dt,
                        purchase_order_id: purchase_order_id,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        console.log("add click");
                        console.log(response);
                        $("#add-new").prop("disabled", false);
                        load_data_detail(purchase_order_id);
                        // Handle the response here
                    },
                    error: function (error) {
                        console.log("Ajax request failed");
                    },
                });
            }
        });
    });

    $(document).on("click", "#cancel", function () {
        console.log("cancel click");
        $("#add-new").prop("disabled", false);
        no = no - 1;
        $(this).closest("tr").remove();
    });

    $("#saveButton").on("click", () => {
        let purchase_order_id = $("#purchase_order_id").val();
        $.ajax({
            type: "POST",
            url: "/submit-purchase_order", // Use the route function to generate the URL
            data: {
                purchase_order_id: purchase_order_id,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log("add click");
                console.log(response);
                $("#add-new").prop("disabled", false);
                load_data_detail(purchase_order_id);
                // Handle the response here
            },
            error: function (error) {
                console.log("Ajax request failed");
            },
        });
    });
});

async function load_data_detail(purchase_order_id) {
    $("#detail-list").show(100);
    $("#container-table tbody").html("");
    console.log("load data here...");
    const headers = new Headers({
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    });

    const dataFechhDetail = await fetch(
        "/getPurchase_order_detail/" + purchase_order_id,
        {
            method: "GET",
            headers: headers,
        }
    )
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });

    console.log("dataFechhDetail => ");
    // console.log(dataFechhDetail);

    var html_load_row = "";
    dataFechhDetail.map((item, index) => {
        no = index += 1;
        $("#addDetail").hide();
        // table detail //
        html_load_row +=
            `<tr>
                <td>` +
            item.sequence +
            `   </td>
                <td>` +
            item.products.name +
            ` / ` +
            item.products.categories.name +
            ` / ` +
            item.products.sizes.initial +
            `   </td>
                <td style="text-align: right;">` +
            item.qty +
            `   </td>
                <td style="text-align: right;">` +
            item.price +
            `   </td>
                <td style="text-align: center">
                    <button type="button" id="edit" class="btn text-warning p-0"> <i class="fas fa-edit"></i> </button>|
                    <button type="button" id="delete" class="btn text-danger p-0"> <i class="fas fa-trash-alt"></i> </button>
                </td>
            </tr>`;
    });
    // console.log(html_load_row);
    $("#container-table tbody").append(html_load_row);
}
