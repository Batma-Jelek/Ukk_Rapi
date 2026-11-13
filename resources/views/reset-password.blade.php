<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Sandi - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-page">
    <main class="login-container">
        <section class="login-card" style="max-width: 480px; margin: 0 auto;" aria-labelledby="resetPasswordTitle">
            <div class="login-header">
                <span class="login-form-eyebrow">RAPI-SEKOLAH</span>
                <h1 id="resetPasswordTitle">Ubah sandi</h1>
                <p>Gunakan sandi baru minimal 8 karakter.</p>
            </div>

            <div id="resetPasswordError" class="alert-danger" role="alert" hidden></div>
            <div id="resetPasswordSuccess" class="forgot-password-success" role="status" hidden></div>

            <form id="resetPasswordForm">
                <div class="form-group">
                    <label for="resetUsername">Username</label>
                    <input type="text" id="resetUsername" class="form-control" autocomplete="username" readonly required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Sandi baru</label>
                    <input type="password" id="newPassword" class="form-control" minlength="8"
                        autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Ulangi sandi baru</label>
                    <input type="password" id="confirmPassword" class="form-control" minlength="8"
                        autocomplete="new-password" required>
                </div>
                <button type="submit" id="resetPasswordSubmit" class="btn btn-primary">Simpan Sandi Baru</button>
            </form>

            <a href="/login" class="login-back-link">Kembali ke halaman masuk</a>
        </section>
    </main>

    <script>
        const query = new URLSearchParams(window.location.search);
        const token = query.get('token') || '';
        const username = query.get('username') || '';
        const form = document.getElementById('resetPasswordForm');
        const errorBox = document.getElementById('resetPasswordError');
        const successBox = document.getElementById('resetPasswordSuccess');
        const submitButton = document.getElementById('resetPasswordSubmit');

        document.getElementById('resetUsername').value = username;
        if (!token || !username) {
            errorBox.textContent = 'Tautan reset sandi tidak lengkap. Minta admin mengirim tautan baru.';
            errorBox.hidden = false;
            form.hidden = true;
        }

        form.addEventListener('submit', async event => {
            event.preventDefault();
            errorBox.hidden = true;
            successBox.hidden = true;
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username,
                        token,
                        password: document.getElementById('newPassword').value,
                        password_confirmation: document.getElementById('confirmPassword').value
                    })
                });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    const validationMessage = Object.values(result.errors || {}).flat()[0];
                    throw new Error(validationMessage || result.message || 'Sandi gagal diubah.');
                }

                form.hidden = true;
                successBox.textContent = result.message;
                successBox.hidden = false;
            } catch (error) {
                errorBox.textContent = error.message || 'Terjadi kesalahan saat mengubah sandi.';
                errorBox.hidden = false;
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Sandi Baru';
            }
        });
    </script>
</body>

</html>
