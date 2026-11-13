// Interaksi navigasi sidebar pada layar kecil.
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('menuToggle');
    // Tombol menu mengubah class agar sidebar dapat dibuka/ditutup di mobile.
    if (sidebar && toggle) toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
});
