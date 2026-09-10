window.onload = function() {
    let namaUser = prompt("Tuliskan nama Kamu");
    let wadahTeks = document.getElementById("isi-form");

    if (namaUser !== null && namaUser.trim() !== "") { 
        wadahTeks.innerHTML = "<b>Nama saya " + namaUser + ", saya akan mengamalkan Pancasila dan UUD 1945 sebagai Dasar Negara.</b>";
    } else {
        window.location.href = "https://www.google.com";
    }
};