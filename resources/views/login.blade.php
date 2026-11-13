<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

<div class="login-container">
    <div class="login-layout">
        <section class="login-intro" aria-label="Tentang RAPI-Sekolah">
            <a href="/" class="login-brand" aria-label="Kembali ke kalender publik">
                <span class="login-brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 40 40" focusable="false">
                        <path d="m5 16 15-10 15 10v2H5v-2Z" fill="currentColor" opacity=".92"/>
                        <path d="M8 19h24v13H8z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M5 34h30M12 22v8m6-8v8m10-8v8m-8 0v-7h4v7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="20" cy="14" r="1.5" fill="#0284c7"/>
                    </svg>
                </span>
                <span>RAPI<span>-Sekolah</span></span>
            </a>
            <div class="login-intro-copy">
                <span class="login-eyebrow">RUANGAN TERTATA, KEGIATAN LANCAR</span>
                <h1>Selamat datang kembali.</h1>
                <p>Masuk untuk mengelola jadwal ruangan dan kebutuhan sekolah dengan lebih mudah.</p>
            </div>
            <div class="login-illustration" aria-hidden="true">
                <span class="login-illustration-orbit"></span>
                <span class="login-illustration-card">
                    <span class="login-illustration-card-head"></span>
                    <span class="login-illustration-lines"><i></i><i></i><i></i></span>
                    <span class="login-illustration-check">✓</span>
                </span>
            </div>
            <p class="login-intro-footer">Sistem Pengelolaan Ruangan &amp; Kerusakan</p>
        </section>

        <section class="login-card" aria-labelledby="loginTitle">
            <div class="login-header">
                <span class="login-mobile-mark" aria-hidden="true">
                    <svg viewBox="0 0 40 40" focusable="false">
                        <path d="m5 16 15-10 15 10v2H5v-2Z" fill="currentColor" opacity=".92"/>
                        <path d="M8 19h24v13H8z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M5 34h30M12 22v8m6-8v8m10-8v8m-8 0v-7h4v7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="20" cy="14" r="1.5" fill="#0284c7"/>
                    </svg>
                </span>
                <span class="login-form-eyebrow">PORTAL PETUGAS SEKOLAH</span>
                <h2 id="loginTitle">Masuk ke akun</h2>
                <p>Gunakan username dan sandi akun Anda.</p>
            </div>

            <div id="errorMessage" class="alert-danger" role="alert" aria-live="polite"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" placeholder="Masukkan username" autocomplete="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="login-password-field">
                        <input type="password" id="password" class="form-control" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button type="button" id="togglePassword" class="login-password-toggle" aria-label="Tampilkan password" aria-pressed="false">Lihat</button>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-primary login-submit">
                    <span>Masuk</span><span aria-hidden="true"></span>
                </button>
                <button type="button" id="forgotPasswordButton" class="forgot-password-button" aria-haspopup="dialog" aria-controls="forgotPasswordModal">
                    Lupa sandi?
                </button>
            </form>

            <a href="/" class="login-back-link">← Kembali ke kalender publik</a>
        </section>
    </div>
</div>

<div id="forgotPasswordModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="forgotPasswordTitle" hidden>
    <div class="modal-content forgot-password-modal">
        <button type="button" id="closeForgotPasswordModal" class="modal-close" aria-label="Tutup popup">&times;</button>
        <h2 id="forgotPasswordTitle">Form Pengajuan Lupa Sandi</h2>
        <p class="forgot-password-description">
            Isi data akun yang terdaftar. Jika cocok, WhatsApp akan terbuka dengan tautan ubah sandi. Tekan Kirim di WhatsApp untuk mengirim pesan.
        </p>
        <div id="forgotPasswordError" class="alert-danger" role="alert"></div>
        <div id="forgotPasswordSuccess" class="forgot-password-success" role="status" hidden></div>
        <a id="forgotPasswordWhatsAppLink" class="btn btn-primary" target="_blank" rel="noopener noreferrer" hidden>
            Buka WhatsApp
        </a>
        <form id="forgotPasswordForm">
            <div class="form-group">
                <label for="forgotUsername">Username</label>
                <input type="text" id="forgotUsername" class="form-control" maxlength="255" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="forgotFullname">Nama lengkap</label>
                <input type="text" id="forgotFullname" class="form-control" maxlength="255" autocomplete="name" required>
            </div>
            <div class="form-group">
                <label for="forgotPhoneNumber">Nomor HP terdaftar</label>
                <input type="tel" id="forgotPhoneNumber" class="form-control" maxlength="20" autocomplete="tel" required>
            </div>
            <button type="submit" id="forgotPasswordSubmit" class="btn btn-primary">Kirim via WhatsApp</button>
        </form>
    </div>
</div>

<script>
const forgotPasswordModal = document.getElementById('forgotPasswordModal');
const forgotPasswordButton = document.getElementById('forgotPasswordButton');
const forgotPasswordForm = document.getElementById('forgotPasswordForm');
const forgotPasswordError = document.getElementById('forgotPasswordError');
const forgotPasswordSuccess = document.getElementById('forgotPasswordSuccess');
const forgotPasswordSubmit = document.getElementById('forgotPasswordSubmit');
const forgotPasswordWhatsAppLink = document.getElementById('forgotPasswordWhatsAppLink');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', function() {
    const showPassword = passwordInput.type === 'password';
    passwordInput.type = showPassword ? 'text' : 'password';
    togglePassword.textContent = showPassword ? 'Sembunyikan' : 'Lihat';
    togglePassword.setAttribute('aria-label', showPassword ? 'Sembunyikan password' : 'Tampilkan password');
    togglePassword.setAttribute('aria-pressed', String(showPassword));
});

function openForgotPasswordModal() {
    forgotPasswordModal.hidden = false;
    forgotPasswordModal.style.display = 'flex';
    document.getElementById('forgotUsername').focus();
}

function hideForgotPasswordModal() {
    forgotPasswordModal.hidden = true;
    forgotPasswordModal.style.display = 'none';
    forgotPasswordButton.focus();
}

forgotPasswordButton.addEventListener('click', openForgotPasswordModal);
document.getElementById('closeForgotPasswordModal').addEventListener('click', hideForgotPasswordModal);
forgotPasswordModal.addEventListener('click', function(event) {
    if (event.target === forgotPasswordModal) hideForgotPasswordModal();
});
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !forgotPasswordModal.hidden) hideForgotPasswordModal();
});

forgotPasswordForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    forgotPasswordError.style.display = 'none';
    forgotPasswordSuccess.hidden = true;
    forgotPasswordWhatsAppLink.hidden = true;
    forgotPasswordSubmit.disabled = true;
    forgotPasswordSubmit.innerText = 'Menyiapkan tautan...';
    const whatsappWindow = window.open('about:blank', '_blank');

    try {
        const response = await fetch('/api/password-reset-requests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: document.getElementById('forgotUsername').value.trim(),
                fullname: document.getElementById('forgotFullname').value.trim(),
                phone_number: document.getElementById('forgotPhoneNumber').value.trim()
            })
        });
        const result = await response.json();

        if (!response.ok) {
            const validationMessage = result.errors
                ? Object.values(result.errors).flat()[0]
                : result.message;
            throw new Error(validationMessage || 'Pengajuan gagal dikirim.');
        }

        const whatsappUrl = result.data?.whatsapp_url;
        if (whatsappUrl && whatsappWindow) {
            whatsappWindow.location.href = whatsappUrl;
        } else if (whatsappUrl) {
            forgotPasswordWhatsAppLink.href = whatsappUrl;
            forgotPasswordWhatsAppLink.hidden = false;
        } else if (whatsappWindow) {
            whatsappWindow.close();
        }

        forgotPasswordForm.hidden = true;
        forgotPasswordSuccess.textContent = whatsappUrl
            ? 'WhatsApp terbuka dengan tautan ubah sandi. Tekan Kirim di WhatsApp untuk mengirim pesan.'
            : result.message;
        forgotPasswordSuccess.hidden = false;
    } catch (error) {
        if (whatsappWindow) whatsappWindow.close();
        forgotPasswordError.textContent = error.message || 'Gagal terhubung ke server backend.';
        forgotPasswordError.style.display = 'block';
    } finally {
        forgotPasswordSubmit.disabled = false;
        forgotPasswordSubmit.innerText = 'Kirim via WhatsApp';
    }
});

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const usernameInput = document.getElementById('username').value;
    const passwordInput = document.getElementById('password').value;
    const errorBox = document.getElementById('errorMessage');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnSubmitLabel = btnSubmit.querySelector('span');

    errorBox.style.display = 'none';
    btnSubmit.disabled = true;
    btnSubmitLabel.innerText = 'Memproses...';

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: usernameInput,
                password: passwordInput
            })
        });

        const result = await response.json();

        if (response.ok) {
            // Simpan Token dan Data User di browser
            localStorage.setItem('token', result.token);
            localStorage.setItem('user', JSON.stringify(result.user));

            // Redirect berdasarkan role_id
            if (result.user.role_id === 1) {
                window.location.href = '/admin/dashboard';
            } else if (result.user.role_id === 2) {
                window.location.href = '/pic/dashboard';
            } else if (result.user.role_id === 3) {
                window.location.href = '/guru/dashboard';
            } else if (result.user.role_id === 4) {
                window.location.href = '/organisasi/dashboard';
            }
        } else {
            errorBox.innerText = result.message || 'Login gagal. Periksa kredensial Anda.';
            errorBox.style.display = 'block';
        }
    } catch (err) {
        errorBox.innerText = 'Gagal terhubung ke server backend.';
        errorBox.style.display = 'block';
    } finally {
        btnSubmit.disabled = false;
        btnSubmitLabel.innerText = 'Masuk';
    }
});
</script>

</body>
</html>