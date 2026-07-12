<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Axioma | Gestión de Usuarios</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body users-page">
    <div class="users-shell">
        <header class="users-header">
            <div>
                <p class="users-eyebrow">Panel administrativo</p>
                <h1>Personal & Usuarios</h1>
                <p>Listado de cuentas corporativas, clientes y administración de accesos del sistema Axioma.</p>
            </div>
        </header>

        <div class="users-grid">
            <section class="users-card users-form-card">
                <div class="card-head">
                    <div>
                        <p class="card-kicker">Gestión</p>
                        <h2 id="formTitle">Registrar Nuevo Personal</h2>
                    </div>
                </div>

                <form id="userCrudForm" class="users-form">
                    <input type="hidden" id="userId" value="">

                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" id="username" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" placeholder="Escriba la clave">
                        <p id="passHelp" class="field-help hidden">Dejar en blanco si no desea cambiarla.</p>
                    </div>

                    <div class="form-group">
                        <label for="rol">Rol del Sistema</label>
                        <select id="rol">
                            <option value="Cliente">Cliente</option>
                            <option value="Vendedor">Vendedor</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>

                    <div id="formMsg" class="message hidden"></div>

                    <div class="button-row compact-row">
                        <button type="submit" id="saveBtn" class="btn btn-primary btn-block">Guardar Usuario</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary hidden">Cancelar</button>
                    </div>
                </form>
            </section>

            <section class="users-card users-table-card">
                <div class="users-toolbar">
                    <div>
                        <p class="card-kicker">Directorio</p>
                        <h2>Usuarios registrados</h2>
                    </div>
                    <div class="search-box">
                        <input type="search" id="searchInput" placeholder="Buscar por nombre o usuario">
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Identificador</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usuariosTableBody">
                            <tr>
                                <td colspan="4" class="empty-state">Cargando base de datos de usuarios...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script>
        let usuariosActivos = [];
        let filtroActual = '';

        document.addEventListener('DOMContentLoaded', () => {
            cargarUsuarios();
            document.getElementById('userCrudForm').addEventListener('submit', guardarUsuario);
            document.getElementById('cancelBtn').addEventListener('click', resetearFormulario);
            document.getElementById('searchInput').addEventListener('input', (event) => {
                filtroActual = event.target.value.trim().toLowerCase();
                renderTabla();
            });
        });

        async function cargarUsuarios() {
            const tbody = document.getElementById('usuariosTableBody');
            tbody.innerHTML = '<tr><td colspan="4" class="empty-state">Cargando base de datos de usuarios...</td></tr>';

            try {
                const res = await fetch('../../../backend/src/server.php?action=get_usuarios');
                const result = await res.json();

                if (result.success) {
                    usuariosActivos = result.data || [];
                    renderTabla();
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="empty-state">No fue posible cargar la información.</td></tr>';
                }
            } catch (error) {
                console.error('Fallo al conectar con el servidor', error);
                tbody.innerHTML = '<tr><td colspan="4" class="empty-state">No fue posible conectar con el servidor.</td></tr>';
            }
        }

        function renderTabla() {
            const tbody = document.getElementById('usuariosTableBody');
            tbody.innerHTML = '';

            const filtrados = usuariosActivos.filter((user) => {
                if (!filtroActual) return true;
                const texto = `${user.nombre} ${user.usuario} ${user.email} ${user.rol}`.toLowerCase();
                return texto.includes(filtroActual);
            });

            if (filtrados.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="empty-state">No se encontraron coincidencias.</td></tr>';
                return;
            }

            filtrados.forEach((user) => {
                let badgeClass = 'user-badge user-badge-default';
                if (user.rol === 'Administrador') badgeClass = 'user-badge user-badge-admin';
                if (user.rol === 'Vendedor') badgeClass = 'user-badge user-badge-vendedor';

                tbody.innerHTML += `
                    <tr>
                        <td>
                            <div class="user-name">${user.nombre}</div>
                            <div class="user-meta">${user.email}</div>
                        </td>
                        <td><span class="user-id">@${user.usuario}</span></td>
                        <td><span class="${badgeClass}">${user.rol}</span></td>
                        <td>
                            <div class="table-actions">
                                <button type="button" class="table-action-btn edit" onclick="prepararEdicion(${user.id})">Editar</button>
                                <button type="button" class="table-action-btn delete" onclick="eliminarUsuario(${user.id})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        async function guardarUsuario(event) {
            event.preventDefault();

            const id = document.getElementById('userId').value;
            const actionType = id ? 'update_usuario' : 'register';
            const saveBtn = document.getElementById('saveBtn');

            saveBtn.disabled = true;
            saveBtn.textContent = 'Guardando...';

            const payload = {
                action: actionType,
                id: id,
                nombre: document.getElementById('nombre').value,
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                rol: document.getElementById('rol').value
            };

            try {
                const res = await fetch('../../../backend/src/server.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                mostrarMensaje(data.message, data.success);

                if (data.success) {
                    resetearFormulario();
                    await cargarUsuarios();
                }
            } catch (error) {
                console.error('Error al guardar el usuario', error);
                mostrarMensaje('No se pudo conectar con el servidor.', false);
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = id ? 'Actualizar Cambios' : 'Guardar Usuario';
            }
        }

        function prepararEdicion(id) {
            const user = usuariosActivos.find((item) => item.id == id);
            if (!user) return;

            document.getElementById('userId').value = user.id;
            document.getElementById('nombre').value = user.nombre;
            document.getElementById('username').value = user.usuario;
            document.getElementById('email').value = user.email;
            document.getElementById('rol').value = user.rol;

            document.getElementById('password').required = false;
            document.getElementById('passHelp').classList.remove('hidden');
            document.getElementById('formTitle').textContent = 'Modificar Credenciales';
            document.getElementById('saveBtn').textContent = 'Actualizar Cambios';
            document.getElementById('cancelBtn').classList.remove('hidden');
        }

        async function eliminarUsuario(id) {
            if (!confirm('¿Está seguro de revocar los accesos y eliminar permanentemente este usuario?')) return;

            try {
                const res = await fetch('../../../backend/src/server.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_usuario', id: id })
                });

                const data = await res.json();
                if (data.success) {
                    await cargarUsuarios();
                    mostrarMensaje('Usuario eliminado correctamente.', true);
                } else {
                    mostrarMensaje(data.message, false);
                }
            } catch (error) {
                console.error('Error al eliminar el usuario', error);
                mostrarMensaje('No se pudo eliminar el usuario.', false);
            }
        }

        function resetearFormulario() {
            document.getElementById('userCrudForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('password').required = true;
            document.getElementById('passHelp').classList.add('hidden');
            document.getElementById('formTitle').textContent = 'Registrar Nuevo Personal';
            document.getElementById('saveBtn').textContent = 'Guardar Usuario';
            document.getElementById('cancelBtn').classList.add('hidden');
        }

        function mostrarMensaje(texto, exito) {
            const msg = document.getElementById('formMsg');
            msg.textContent = texto;
            msg.className = `message ${exito ? 'success' : 'error'}`;
            clearTimeout(mostrarMensaje.timer);
            mostrarMensaje.timer = setTimeout(() => {
                msg.className = 'message hidden';
            }, 3000);
        }
    </script>
</body>
</html>