let nama = prompt("Tuliskan nama Kamu");

if (nama === null || nama.trim() === "") {
    nama = "Nama saya";
} else {
    nama = nama.trim();
}

let paragrafNama = document.createElement("p");

paragrafNama.className = "kalimat-nama";

paragrafNama.innerHTML =
    "<strong>Nama saya " + nama +
    ", saya akan mengamalkan Pancasila dan UUD 1945 sebagai Dasar Negara.</strong>";

document.body.insertBefore(
    paragrafNama,
    document.querySelector(".judul")
);