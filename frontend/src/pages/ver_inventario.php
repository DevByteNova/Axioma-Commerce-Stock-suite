<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="frontend/src/public/css/styles.css">
</head>
<body>
    <div class="auth-container" style="max-width: 1000px; margin: 0 auto; padding: 30px 20px;">
    <div class="form-card" style="max-width: 100%; width: 100%; text-align: center;">
        <h2 style="text-align: center;">Inventario de Productos</h2>
        <table id="tablaInventario" border="1" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Nombre</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Descripción</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Precio</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Stock</th>
                </tr>
            </thead>
            <tbody id="cuerpoInventario">
                <!-- Los datos se cargarán con JavaScript -->
            </tbody>
        </table>
        <a href="index.php?url=dashboard" style="display: inline-block; margin-top: 20px; text-align: center;">Volver al Dashboard</a>
    </div>
</div>

<script>
async function cargarInventario() {
    try {
        const res = await fetch('./backend/src/server.php?action=listar_inventario');
        const productos = await res.json();
        
        const tbody = document.getElementById('cuerpoInventario');
        if (!Array.isArray(productos) || productos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 15px;">No hay productos registrados.</td></tr>`;
            return;
        }

        tbody.innerHTML = productos.map(p => {
            const id = p.ID_PRODUCTO ?? p.id ?? '';
            const nombre = p.nombre ?? p.NOMBRE ?? 'Sin nombre';
            const descripcion = p.DESCRIPCION ?? p.descripcion ?? 'Sin descripción';
            const precio = p.PRECIO ?? p.precio ?? 0;
            const stock = p.STOCK ?? p.stock ?? 0;

            return `
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">${id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${nombre}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${descripcion}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">$${parseFloat(precio).toFixed(2)}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; text-align: center; font-weight: bold;">${stock}</td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error("Error al cargar el inventario:", error);
    }
}

cargarInventario();
</script>
</body>
</html>