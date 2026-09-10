// Popup muncul ketika halaman dibuka
let nama = prompt("Tuliskan nama Kamu");

// Jika nama diisi
if (nama !== null && nama.trim() !== "") {

    // Tampilkan konten halaman
    document.querySelector(".container").style.display = "block";

    // Masukkan nama ke kalimat yang di html 
    document.querySelector(".nama-saya").innerHTML =
        "Nama saya " + nama +
        ", saya akan mengamalkan Pancasila dan UUD 1945 sebagai Dasar Negara.";

} else {

    // Kalau batal atau tidak mengisi,
    // konten tetap disembunyikan
    document.querySelector(".container").style.display = "none";
}