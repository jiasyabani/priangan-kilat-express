// Interaksi sederhana pada halaman login; tidak memeriksa akun ke database.
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    const form = document.getElementById('loginForm');
    const error = document.getElementById('loginError');
    // Tampilkan/sembunyikan password ketika ikon mata diklik.
    if (password && toggle) toggle.addEventListener('click', () => {
        password.type = password.type === 'password' ? 'text' : 'password';
        toggle.innerHTML = password.type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
    // Validasi input kosong di browser; validasi keamanan wajib dilakukan server.
    if (form) form.addEventListener('submit', (event) => {
        if (!document.getElementById('username').value.trim() || !password.value.trim()) {
            event.preventDefault();
            error.textContent = 'Email/username dan password wajib diisi.';
        }
    });
});
