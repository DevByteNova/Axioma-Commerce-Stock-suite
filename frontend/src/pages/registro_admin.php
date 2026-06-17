<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-900"> <head>
    <meta charset="UTF-8">
    <title>Axioma | Alta de Dirección</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto w-full max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">Registro de Infraestructura</h2>
        <p class="mt-2 text-center text-sm text-slate-400">Alta secreta para cuentas con rol de **Administrador**</p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-md">
        <div class="bg-white px-4 py-8 shadow-md sm:rounded-lg sm:px-10 border border-slate-200">
            <form id="registerAdminForm" class="space-y-6">
                <input type="hidden" id="rol" value="Administrador"> 

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nombre del Administrador</label>
                    <input type="text" id="nombre" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Usuario Master</label>
                    <input type="text" id="username" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Correo Institucional</label>
                    <input type="email" id="email" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Contraseña de Acceso</label>
                    <input type="password" id="password" required class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-slate-900">
                </div>

                <div id="msg" class="hidden text-sm p-3 rounded-md"></div>

                <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-md font-semibold hover:bg-slate-800 transition-colors cursor-pointer">
                    Dar de Alta Administrador
                </button>
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
                    rol: document.getElementById('rol').value // Enviará "Administrador"
                })
            });
            const data = await res.json();
            const msg = document.getElementById('msg');
            msg.textContent = data.message;
            msg.classList.remove('hidden', 'bg-red-50', 'text-red-600', 'bg-green-50', 'text-green-600');
            
            if(data.success) {
                msg.classList.add('bg-green-50', 'text-green-600');
                // Al tener éxito, lo mandamos al login para que estrene su cuenta
                setTimeout(() => window.location.href = 'index.php?url=login', 2000);
            } else {
                msg.classList.add('bg-red-50', 'text-red-600');
            }
        });
    </script>
</body>
</html>