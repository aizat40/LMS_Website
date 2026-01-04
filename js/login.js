const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

if (registerBtn) {
    registerBtn.addEventListener('click', () => {
        container.classList.add('active');
    });
}

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        container.classList.remove('active');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil parameter daripada URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // 2. Gunakan Logik If-Else atau Switch untuk menentukan modal mana yang perlu keluar
    if (status === 'success') {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    } 
    else if (status === 'invalid') {
        const invalidModal = new bootstrap.Modal(document.getElementById('invalidModal'));
        invalidModal.show();
    }

    // 3. Bersihkan URL selepas modal dipaparkan (UX: Supaya modal tak keluar lagi bila refresh)
    if (status) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

document.getElementById('invalidModal').addEventListener('hidden.bs.modal', function () {
    document.querySelector('input[placeholder="Username"]').focus();
});
