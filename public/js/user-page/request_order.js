const image = document.getElementById("image");
let blah = document.getElementById("blah");
image.onchange = (evt) => {
    const [file] = image.files;
    if (file) {
        blah.src = URL.createObjectURL(file);
    }
};
