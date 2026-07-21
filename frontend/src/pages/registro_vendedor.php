    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="frontend/src/public/css/styles.css">
    </head>
    <body>
    <div class="auth-container">
        <div class="form-card">
            <h2>Añadir nuevo Vendedor</h2>
            <form id="registroVendedorForm">
                <div class="form-group">
                    <label>Nombre de Usuario</label>
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
                <button type="submit" id="submitBtn">Crear Vendedor</button>
            </form>
            <p id="mensaje"></p>
            <a href="index.php?url=dashboard">Volver al Dashboard</a>
        </div>
    </div>

    <script>
    document.getElementById('registroVendedorForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = "Procesando...";

        const data = {
            action: 'registrar_vendedor',
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        const response = await fetch('/Axioma-Commerce-Stock-suite/backend/src/server.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        const msg = document.getElementById('mensaje');
        msg.textContent = result.message;
        msg.style.color = result.success ? "green" : "red";
        btn.disabled = false;
        btn.textContent = "Crear Vendedor";
    });
    </script>
    </body>
    </html>