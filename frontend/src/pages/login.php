<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Axioma | Inicio de Sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="h-full">

    <div class="flex min-h-full">
        
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-slate-50">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <div>
                    <div class="flex items-center gap-2 text-slate-900 font-bold text-2xl tracking-wider">
                        <div class="h-8 w-8 rounded-lg bg-slate-900 flex items-center justify-center text-white text-sm">∀</div>
                        <span>AXIOMA</span>
                    </div>
                    <h2 class="mt-8 text-2xl font-bold tracking-tight text-slate-900">Iniciar sesión en su cuenta</h2>
                    <p class="mt-2 text-sm text-slate-500">Introduzca sus credenciales para acceder al sistema operativo.</p>
                </div>

                <div class="mt-10">
                    <form id="loginForm" class="space-y-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-slate-700">Usuario o Correo Corporativo</label>
                            <div class="mt-2">
                                <input type="text" id="username" name="username" required autocomplete="username"
                                    class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-base text-slate-900 focus:border-slate-900 focus:outline-hidden sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Contraseña</label>
                            <div class="mt-2">
                                <input type="password" id="password" name="password" required autocomplete="current-password"
                                    class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-base text-slate-900 focus:border-slate-900 focus:outline-hidden sm:text-sm">
                            </div>
                        </div>

                        <div id="errorMessage" class="hidden text-sm font-medium text-red-600 bg-red-50 border border-red-200 p-3 rounded-md"></div>

                        <div>
                            <button type="submit" id="submitBtn"
                                class="flex w-full justify-center rounded-md bg-slate-900 px-3 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-slate-800 transition-all duration-200 cursor-pointer disabled:opacity-50">
                                <span id="btnText">Ingresar al Sistema</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-20 border-t border-slate-200 pt-6">
                    <p class="text-xs text-slate-400 leading-normal">
                        Conexión cifrada de entorno seguro. El acceso no autorizado está estrictamente monitoreado y auditado por la administración de Axioma.
                    </p>
                </div>

            </div>
        </div>

        <div class="relative hidden w-0 flex-1 lg:block bg-slate-950">
            <div class="absolute inset-0 h-full w-full bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:16px_16px] opacity-40 flex flex-col justify-center px-16 text-white">
                <div class="max-w-md space-y-4">
                    <span class="text-xs font-semibold tracking-widest text-slate-400 uppercase bg-slate-900 px-3 py-1 rounded-full border border-slate-800">Enterprise Suite</span>
                    <h1 class="text-4xl font-extrabold tracking-tight text-white lg:text-5xl">
                        Precisión lógica en cada transacción.
                    </h1>
                    <p class="text-base text-slate-400 leading-relaxed">
                        Optimice sus flujos de venta y mantenga el control absoluto de su inventario en tiempo real con la infraestructura de Axioma.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        // 1. Evitar que la página se recargue y limpie la consola
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
            console.log("Enviando datos al servidor...", { username: usernameInput });

            const response = await fetch('backend/src/server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'login',
                    username: usernameInput,
                    password: passwordInput
                })
            });

            // Ver qué responde textualmente el servidor antes de transformarlo a JSON
            const rawText = await response.text();
            console.log("Respuesta cruda del servidor:", rawText);

            // Convertir a objeto JSON
            const data = JSON.parse(rawText);

            if (data.success) {
             console.log("Login exitoso, redirigiendo...");
                window.location.assign = "index.php?url=dashboard";
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
            console.error("Error capturado en el catch:", error);
        
            alert("Error crítico en la petición: " + error.message); 
            
            errorMessage.textContent = "Error de conexión con el servidor de Axioma.";
            errorMessage.classList.remove('hidden');
            submitBtn.disabled = false;
            btnText.textContent = "Ingresar al Sistema";
        }
    });
    </script>
</body>
</html>