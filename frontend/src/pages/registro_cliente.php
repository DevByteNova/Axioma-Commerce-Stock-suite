<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Axioma | Registro de Cliente</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body">
    <div class="form-shell">
        <div class="form-card">
            <h2>Crear cuenta de Cliente</h2>
            <p class="form-subtitle">Únete a nuestra plataforma comercial</p>

            <form id="registerForm" class="auth-form">
                <input type="hidden" id="rol" value="Cliente">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" id="nombre" required>
                </div>
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" id="username" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" id="password" required>
                </div>

                <div id="msg" class="message hidden"></div>

                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const res = await fetch('backend/src/server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'register',
                    nombre: document.getElementById('nombre').value,
                    username: document.getElementById('username').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    rol: document.getElementById('rol').value
                })
            });
            const data = await res.json();
            const msg = document.getElementById('msg');
            msg.textContent = data.message;
            msg.classList.remove('hidden');

            if(data.success) {
                msg.classList.add('success');
                setTimeout(() => window.location.href = 'index.php?url=login', 2000);
            } else {
                msg.classList.add('error');
            }
        });
    </script>
</body>
</html>