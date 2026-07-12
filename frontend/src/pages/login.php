<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Axioma | Inicio de Sesión</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body auth-body">
    <div class="auth-shell">
        <div class="auth-panel auth-panel-form">
            <div class="brand-block">
                <div class="brand-mark">∀</div>
                <span>AXIOMA</span>
            </div>
            <h2>Iniciar sesión en su cuenta</h2>
            <p>Introduzca sus credenciales para acceder al sistema operativo.</p>

            <form id="loginForm" class="auth-form">
                <div class="form-group">
                    <label for="username">Usuario o Correo Corporativo</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <div id="errorMessage" class="message hidden"></div>

                <button type="submit" id="submitBtn" class="btn btn-primary btn-block">
                    <span id="btnText">Ingresar al Sistema</span>
                </button>
            </form>

            <div class="auth-footer">
                <p>Conexión cifrada de entorno seguro. El acceso no autorizado está estrictamente monitoreado y auditado por la administración de Axioma.</p>
            </div>
        </div>

        <div class="auth-panel auth-panel-hero">
            <div class="hero-content">
                <span class="hero-badge">Enterprise Suite</span>
                <h1>Precisión lógica en cada transacción.</h1>
                <p>Optimice sus flujos de venta y mantenga el control absoluto de su inventario en tiempo real con la infraestructura de Axioma.</p>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();

        const usernameInput = document.getElementById('username').value;
        const passwordInput = document.getElementById('password').value;

        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const errorMessage = document.getElementById('errorMessage');

        submitBtn.disabled = true;
        btnText.textContent = "Verificando credenciales...";
        errorMessage.classList.add('hidden');

        try {
            const response = await fetch('backend/src/server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'login',
                    username: usernameInput,
                    password: passwordInput
                })
            });

            const rawText = await response.text();
            const data = JSON.parse(rawText);

            if (data.success) {
                setTimeout(() => {
                    window.location.href = "index.php?url=dashboard";
                }, 500);
            } else {
                errorMessage.textContent = data.message;
                errorMessage.classList.remove('hidden');
                submitBtn.disabled = false;
                btnText.textContent = "Ingresar al Sistema";
            }

        } catch (error) {
            errorMessage.textContent = "Error de conexión con el servidor de Axioma.";
            errorMessage.classList.remove('hidden');
            submitBtn.disabled = false;
            btnText.textContent = "Ingresar al Sistema";
        }
    });
    </script>
</body>
</html>