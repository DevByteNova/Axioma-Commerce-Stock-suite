<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <title>Axioma | Gestión de Usuarios</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="h-full font-sans antialiased text-slate-900">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-200 pb-5">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Personal & Usuarios</h1>
                <p class="mt-2 text-sm text-slate-500">Listado de cuentas corporativas, clientes y administración de accesos del sistema Axioma.</p>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-3">
            
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs h-fit">
                <h2 id="formTitle" class="text-lg font-bold text-slate-900 mb-4">Registrar Nuevo Personal</h2>
                
                <form id="userCrudForm" class="space-y-4">
                    <input type="hidden" id="userId" value="">

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nombre Completo</label>
                        <input type="text" id="nombre" required class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-900 focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nombre de Usuario</label>
                        <input type="text" id="username" required class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-900 focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Correo Electrónico</label>
                        <input type="email" id="email" required class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-900 focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Contraseña</label>
                        <input type="password" id="password" placeholder="Escriba la clave" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-900 focus:outline-hidden">
                        <p id="passHelp" class="mt-1 text-xs text-slate-400 hidden">Dejar en blanco si no desea cambiarla.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Rol del Sistema</label>
                        <select id="rol" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-900 focus:outline-hidden">
                            <option value="Cliente">Cliente</option>
                            <option value="Vendedor">Vendedor</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>

                    <div id="formMsg" class="hidden text-sm p-3 rounded-md font-medium"></div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" id="saveBtn" class="flex-1 bg-slate-900 text-white py-2 rounded-md text-sm font-semibold hover:bg-slate-800 transition-colors cursor-pointer">Guardar Usuario</button>
                        <button type="button" id="cancelBtn" class="hidden flex-1 bg-slate-200 text-slate-700 py-2 rounded-md text-sm font-semibold hover:bg-slate-300 transition-colors cursor-pointer">Cancelar</button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nombre</th>
                                <th class="px-6 py-4">Identificador</th>
                                <th class="px-6 py-4">Rol</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usuariosTableBody" class="divide-y divide-slate-200 bg-white">
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">Cargando base de datos de usuarios...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Array global temporal para guardar los registros cargados
        let usuariosActivos = [];

        // Cargar los usuarios automáticamente al abrir la página
        document.addEventListener('DOMContentLoaded', cargarUsuarios);

        async function cargarUsuarios() {
            try {
                // Cambia la ruta de la API agregando una acción exclusiva para leer usuarios
                const res = await fetch('backend/src/server.php?action=get_usuarios');
                const result = await res.json();
                
                if(result.success) {
                    usuariosActivos = result.data;
                    renderTabla();
                }
            } catch (error) {
                console.error("Fallo al conectar con el servidor", error);
            }
        }

        function renderTabla() {
            const tbody = document.getElementById('usuariosTableBody');
            tbody.innerHTML = '';

            if(usuariosActivos.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No hay registros cargados en la BD.</td></tr>`;
                return;
            }

            usuariosActivos.forEach(user => {
                // Etiqueta de color dinámica según el rol institucional
                let badgeColor = "bg-slate-100 text-slate-800";
                if(user.rol === 'Administrador') badgeColor = "bg-red-50 text-red-700 border border-red-200";
                if(user.rol === 'Vendedor') badgeColor = "bg-blue-50 text-blue-700 border border-blue-200";

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">${user.nombre}<br><span class="text-xs text-slate-400">${user.email}</span></td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">@${user.usuario}</td>
                        <td class="px-6 py-4"><span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ${badgeColor}">${user.rol}</span></td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="prepararEdicion(${user.id})" class="text-slate-600 hover:text-slate-900 text-xs font-semibold cursor-pointer">Editar</button>
                            <button onclick="eliminarUsuario(${user.id})" class="text-red-600 hover:text-red-900 text-xs font-semibold cursor-pointer">Eliminar</button>
                        </td>
                    </tr>
                `;
            });
        }

        // Enviar Formulario (Crear o Editar)
        document.getElementById('userCrudForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('userId').value;
            const actionType = id ? 'update_usuario' : 'register'; // Si hay ID actualiza, si no, registra

            const payload = {
                action: actionType,
                id: id,
                nombre: document.getElementById('nombre').value,
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                rol: document.getElementById('rol').value
            };

            const res = await fetch('backend/src/server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            mostrarMensaje(data.message, data.success);

            if(data.success) {
                resetearFormulario();
                cargarUsuarios();
            }
        });

        function prepararEdicion(id) {
            const user = usuariosActivos.find(u => u.id == id);
            if(!user) return;

            document.getElementById('userId').value = user.id;
            document.getElementById('nombre').value = user.nombre;
            document.getElementById('username').value = user.usuario;
            document.getElementById('email').value = user.email;
            document.getElementById('rol').value = user.rol;
            
            // Ajustes para modo edición de claves
            document.getElementById('password').required = false;
            document.getElementById('passHelp').classList.remove('hidden');
            document.getElementById('formTitle').textContent = "Modificar Credenciales";
            document.getElementById('saveBtn').textContent = "Actualizar Cambios";
            document.getElementById('cancelBtn').classList.remove('hidden');
        }

        async function eliminarUsuario(id) {
            if(!confirm("¿Está seguro de revocar los accesos y eliminar permanentemente este usuario?")) return;

            const res = await fetch('backend/src/server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete_usuario', id: id })
            });

            const data = await res.json();
            if(data.success) {
                cargarUsuarios();
            } else {
                alert(data.message);
            }
        }

        function resetearFormulario() {
            document.getElementById('userCrudForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('password').required = true;
            document.getElementById('passHelp').classList.add('hidden');
            document.getElementById('formTitle').textContent = "Registrar Nuevo Personal";
            document.getElementById('saveBtn').textContent = "Guardar Usuario";
            document.getElementById('cancelBtn').classList.add('hidden');
        }

        document.getElementById('cancelBtn').addEventListener('click', resetearFormulario);

        function mostrarMensaje(txt, exito) {
            const msg = document.getElementById('formMsg');
            msg.textContent = txt;
            msg.className = `text-sm p-3 rounded-md font-medium ${exito ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'}`;
            setTimeout(() => msg.className = "hidden", 3000);
        }
    </script>
</body>
</html>