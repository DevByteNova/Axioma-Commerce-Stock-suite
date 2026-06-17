# Axioma - Panel de Control Administrativo

Sistema de gestión desarrollado bajo una arquitectura modular que integra el frontend, backend y gestión de base de datos para el control de operaciones.

## 🛠️ Tecnologías y Configuración
* **Lenguaje:** PHP 8+
* **Base de Datos:** MySQL gestionado mediante PDO
* **Interfaz:** Tailwind CSS integrado para el diseño de vistas
* **Arquitectura:** Estructura modular dividida en `frontend/` para las vistas y `backend/` para la lógica de conexión y consultas

## 📁 Estructura del Proyecto

Axioma/
├── backend/          # Lógica de conexión y consultas
├── frontend/         # Vistas e interfaz de usuario
├── database/         # Respaldo de la base de datos (.sql)
└── index.php         # Punto de entrada principal


## 🚀 Guía de Implementación
1. **Base de Datos:** Importa el archivo `.sql` ubicado en `/database` a través de phpMyAdmin.
2. **Conexión:** Configura tus credenciales (host, nombre de BD, usuario y clave) en el archivo `backend/src/conexion.php`.
3. **Integración:** Asegúrate de que las rutas relativas o absolutas de `require_once` en tus archivos (`dashboard.php`, etc.) apunten correctamente a `backend/src/conexion.php`.

## 🔒 Seguridad
* Autenticación basada en sesiones PHP con acceso restringido para administradores.
* Implementación de rutas absolutas para garantizar la correcta carga de dependencias en el entorno de desarrollo local.

---
*Desarrollado como proyecto académico.*