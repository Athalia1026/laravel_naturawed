// Inisialisasi global untuk memunculkan ikon Lucide di seluruh halaman Customer
document.addEventListener("DOMContentLoaded", function () {
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }
});
