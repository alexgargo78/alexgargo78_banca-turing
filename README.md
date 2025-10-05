# 🏦 Banca Turing

Aplicación web desarrollada en **PHP**, **MySQL** y **Bootstrap 5**, que
permite gestionar clientes de una entidad bancaria.\
Incluye funcionalidades completas de **listado, alta, modificación,
eliminación y ordenación** de clientes.

------------------------------------------------------------------------

## 📂 Estructura del proyecto

    /banca-turing/
    │
    ├── index.php              → Página principal (listado, modificar, eliminar)
    ├── nuevo_cliente.php      → Formulario para añadir nuevos clientes
    ├── /css/
    │   └── style.css          → Estilos personalizados con fondo, transparencia y glassmorphism
    ├── /img/
    │   └── banca-turing.png   → Imagen de fondo (logos automovilísticos)
    └── README.md              → Este archivo

------------------------------------------------------------------------

## ⚙️ Requisitos

-   **Servidor PHP 8.1 o superior**\
-   **MySQL 5.7 o superior**
-   Extensión `mysqli` habilitada\
-   Navegador moderno compatible con `backdrop-filter` (para el efecto
    vidrio)

------------------------------------------------------------------------

## 🧩 Instalación y configuración

1.  **Sube los archivos** al servidor AlwaysData (o localmente con XAMPP
    / Laragon).\

2.  **Crea la base de datos** en phpMyAdmin con el nombre:

    ``` sql
    alexgargo78_banca-turing
    ```

3.  **Importa la tabla `cliente`:**

    ``` sql
    CREATE TABLE cliente (
      dni VARCHAR(15) PRIMARY KEY,
      nombre VARCHAR(100) NOT NULL,
      direccion VARCHAR(150) NOT NULL,
      telefono VARCHAR(20) NOT NULL
    );
    ```

4.  **Edita las credenciales** de conexión en `index.php` y
    `nuevo_cliente.php` si usas otro servidor:

    ``` php
    $conexion = mysqli_connect(
        "mysql-alexgargo78.alwaysdata.net",
        "432730_",
        "Lequio.78",
        "alexgargo78_banca-turing"
    );
    ```

------------------------------------------------------------------------

## 🖥️ Uso de la aplicación

### 🔹 Página principal: `index.php`

-   Muestra el listado de clientes.\
-   Permite **ordenar** por DNI, nombre, dirección o teléfono (clic en
    el encabezado).\
-   Incluye botones:
    -   🗑️ **Borrar** cliente
    -   ✏️ **Modificar** cliente (edición en línea)
    -   ➕ **Añadir nuevo cliente** → lleva a `nuevo_cliente.php`

### 🔹 Añadir nuevo cliente: `nuevo_cliente.php`

-   Formulario con los campos:
    -   DNI\
    -   Nombre\
    -   Dirección\
    -   Teléfono\
-   Valida que no exista un DNI duplicado antes de insertar.

------------------------------------------------------------------------

## 🎨 Diseño (CSS)

El archivo `style.css` aplica: - Fondo con imagen
(`/img/banca-turing.png`)\
- Efecto **glassmorphism** con:
`css   background-color: rgba(255, 255, 255, 0.18);   backdrop-filter: blur(6px);   border-radius: 12px;` -
Tablas transparentes y con texto claro.\
- Botones `Bootstrap` personalizados (`.btn-success`, `.btn-primary`,
`.btn-danger`).

------------------------------------------------------------------------

## ⚡ Características técnicas

-   CRUD completo (Create, Read, Update, Delete) con control de errores.
-   Uso de **mysqli preparado** (para evitar inyecciones SQL).
-   Ordenación de columnas mediante parámetros GET seguros.
-   Interfaz responsive y moderna con **Bootstrap 5.3.8**.
-   Compatible con AlwaysData.

------------------------------------------------------------------------

## 📸 Captura de ejemplo

![Interfaz de Banca Turing](./img/banca-turing.png)

------------------------------------------------------------------------

## 👨‍💻 Autor

**Alejandro García Gómez**\
Proyecto educativo --- *Ejercicio Banca Turing Mejorado (2025)*\
Desarrollado para prácticas de bases de datos y PHP.
