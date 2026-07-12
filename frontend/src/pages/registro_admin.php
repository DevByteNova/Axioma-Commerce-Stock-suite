<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Axioma | Alta de Dirección</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body auth-body dark-body">
    <div class="form-shell">
        <div class="form-card auth-card">
            <h2>Registro de Infraestructura</h2>
            <p class="form-subtitle">Alta secreta para cuentas con rol de Administrador</p>

            <form id="registerAdminForm" class="auth-form">
                <input type="hidden" id="rol" value="Administrador">

                <div class="form-group">
                    <label>Nombre del Administrador</label>
                    <input type="text" id="nombre" required>
                </div>
                <div class="form-group">
                    <label>Usuario Master</label>
                    <input type="text" id="username" required>
                </div>
                <div class="form-group">
                    <label>Correo Institucional</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label>Contraseña de Acceso</label>
                    <input type="password" id="password" required>
                </div>

                <div id="msg" class="message hidden"></div>

                <button type="submit" class="btn btn-primary btn-block">Dar de Alta Administrador</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('registerAdminForm').addEventListener('submit', async function(e) {
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