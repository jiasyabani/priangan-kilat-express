
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');

    if (password && toggle) {
        toggle.addEventListener('click', () => {
            password.type = password.type === 'password' ? 'text' : 'password';

            toggle.innerHTML = password.type === 'password'
                ? '<i class="bi bi-eye"></i>'
                : '<i class="bi bi-eye-slash"></i>';
        });
    }
});
