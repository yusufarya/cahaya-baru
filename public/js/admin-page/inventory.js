function popupImg(img) {
    $("#modal-detail").modal("show");

    $("#img_produk").attr("src", "/storage/" + img);
}
