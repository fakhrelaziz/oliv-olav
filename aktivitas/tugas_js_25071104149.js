window.onload = function () {

  var halamanForm = document.getElementById("halamanForm");
  var halamanIsi = document.getElementById("halamanIsi");
  var inputNama = document.getElementById("inputNama");
  var tombolOke = document.getElementById("tombolOke");
  var tombolBatal = document.getElementById("tombolBatal");
  var teksNamaSaya = document.getElementById("teksNamaSaya");

  function prosesNama() {
    var nama = inputNama.value.trim();

    if (nama === "") {
      alert("Nama tidak boleh kosong, silakan isi terlebih dahulu.");
      return;
    }

    teksNamaSaya.textContent = "Nama saya " + nama + ", saya akan mengamalkan Pancasila dan UUD 1945 sebagai Dasar Negara.";

    halamanForm.style.display = "none";
    halamanIsi.classList.remove("sembunyi");
  }

  function batalkanForm() {
    inputNama.value = "";
  }

  tombolOke.addEventListener("click", prosesNama);
  tombolBatal.addEventListener("click", batalkanForm);

};
