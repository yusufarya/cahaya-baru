$(function () {
    $("#status").on("change", () => {
        $("#submitForm").submit();
    });
    $("#delivery").on("change", () => {
        $("#submitForm").submit();
    });
});
