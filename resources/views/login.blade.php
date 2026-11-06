<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>RAPI-Sekolah</h1>
            <p>Sistem Pengelolaan Ruangan & Kerusakan</p>
        </div>

        <div id="errorMessage" class="alert-danger"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" id="btnSubmit" class="btn btn-primary">Masuk</button>
        </form>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const usernameInput = document.getElementById('username').value;
    const passwordInput = document.getElementById('password').value;
    const errorBox = document.getElementById('errorMessage');
    const btnSubmit = document.getElementById('btnSubmit');

    errorBox.style.display = 'none';
    btnSubmit.innerText = 'Memproses...';

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
        btnSubmit.innerText = 'Masuk';
    }
});
</script>

</body>
</html>