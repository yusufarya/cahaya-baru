const image = document.getElementById("image");
let preview = document.getElementById("preview");
image.onchange = (evt) => {
    const [file] = image.files;
    console.log(file);
    if (file) {
        preview.src = URL.createObjectURL(file);
    }
};
