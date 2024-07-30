// Fungsi untuk konfirmasi penghapusan
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = 'hapus_mahasiswa.php?id=' + id;
    }
}

// Fungsi untuk menampilkan loading spinner
function showLoading() {
    document.getElementById('loadingSpinner').style.display = 'block';
}

// Fungsi untuk menyembunyikan loading spinner
function hideLoading() {
    document.getElementById('loadingSpinner').style.display = 'none';
}

// Event listener untuk form submission
document.addEventListener('DOMContentLoaded', function() {
    var forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            showLoading();
        });
    });
});

// Fungsi untuk inisialisasi tooltip Bootstrap
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})