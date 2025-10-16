# 💰✨ CrediControl – Tu asistente financiero personal

CrediControl es una aplicación web diseñada para ayudarte a llevar el control de tus finanzas personales de forma clara, segura y eficiente. Gestiona tus ingresos, egresos y deudas con una interfaz intuitiva y herramientas inteligentes que te permiten visualizar tu salud financiera.

---

## 🧩 Funcionalidades clave

-   Registro y seguimiento de ingresos y egresos con filtros avanzados.
-   Gestión completa de deudas y deudores.
-   Gestión completa de contactos.
-   Configuración de presupuestos.
-   Visualización de transacciones por nombre, tipo, fecha.
-   Seguridad y rendimiento con arquitectura moderna y escalable.
-   Generación de reportes en pdf.
-   Auditoria de cada acción realizada.

---

## 🔧 Tecnologías utilizadas

-   **Laravel** – Framework PHP robusto y elegante para desarrollo backend, basado en arquitectura por capas y principios SOLID.
-   **MySQL** – Sistema de gestión de bases de datos relacional, ideal para almacenar y consultar transacciones, deudas y auditorías de forma eficiente.

---

## 📖 Historias de Usuario

### 👤 Módulo Auth

-   Como usuario registrado quiero poder iniciar sesión en el sistema para acceder a mis funcionalidades personalizadas y datos privados.
-   Como usuario autenticado quiero poder ver mi perfil personal para revisar mi información.
-   Como usuario autenticado quiero poder cerrar sesión de forma segura para proteger mi cuenta y evitar accesos no autorizados.

### 👤 Módulo Contacto

-   Obtener todos los contactos registrados para gestionar el sistema.
-   Obtener un contactos específico junto con sus deudas para visualizar su estado financiero.
-   Filtrar deudas de contactos por descripción o fecha.
-   Editar la información de un contactos existente.
-   Eliminar un contactos del sistema.

### 💸 Módulo Transacciones

-   Crear una transacción (ingreso o egreso).
-   Editar una transacción.
-   Eliminar una transacción.
-   Obtener todas mis transacciones.
-   Filtrar transacciones por descripción, tipo, fecha de inicio.
-   Configurar mi presupuesto inicial.
-   Ver gráfico de balance de mis ingresos y egresos

### 🧾 Módulo Deudas

-   Crear una deuda a un usuario, monto y fecha.
-   Editar una deuda.
-   Eliminar una deuda.
-   Obtener todas las deudas registradas.
-   Filtrar deudas por nombre, apellido, fecha de inicio, fecha de fin o rango de fechas.
-   Obtener los detalles de una deuda específica.
-   Ver gráfico de top 10 más deudores

### 🧾 Módulo Pagos

-   Registrar el pago de una deuda.
-   Editar los detalles de un pago registrado.
-   Consultar los detalles de un pago específico.
-   Eliminar un pago registrado.
-   Subir imágenes asociadas a un pago.
-   Eliminar una imagen específica asociada a un pago.
-   Eliminar todas las imágenes asociadas a un pago.

### 🕵️‍♂️ Módulo Auditoría

-   Registrar y consultar acciones realizadas por mi en la aplicación.

### 🧾 Módulo Admin (futuro)

-   Registrar y consultar acciones realizadas por los usuarios en la aplicación.
-   Crear un nuevo usuario.
-   Editar los datos de un usuario existente.
-   Ver la lista de todos los usuarios registrados.
-   Ver el detalle de un usuario específico.
-   Filtrar usuarios por nombre, apellido y correo electrónico.
-   Activar o desactivar la cuenta de un usuario.
-   Eliminar un usuario del sistema.
-   Ver la auditoría completa de los usuarios.
-   Exportar la lista de usuarios a PDF.
-   Registrar cada acción relevante en la auditoría del sistema.

---

## 🖼️ Diagramas y Referencias Visuales

-   **Casos de Uso**
-   ![Módulo Auth](/public/assets/auth.png)
-   ![Módulo User](/public/assets/user.png)
-   ![Módulo Transactions](/public/assets/transactions.png)
-   ![Módulo Debs](/public/assets/debts.png)
-   ![Módulo Pay](/public/assets/pays.png)
-   ![Módulo Admin](/public/assets/admin.png)

-   **Modelo de Base de Datos**  
    ![Diagrama de Base de Datos](/public/assets/bd.png)

---

## 🚀 Instalación rápida

```bash
git clone https://github.com//JeanDev-10/CrediControlApp.git
cd crediControl
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm run dev
php artisan serve
```

## APP funcionando

-   ![Dashboard](/public/assets/dashboard.jpeg)
-   ![Contacts](/public/assets/contacts.jpeg)
-   ![Transactions](/public/assets/transactions.jpeg)
-   ![Debs](/public/assets/debts.jpeg)
-   ![Pay](/public/assets/pays.jpeg)
-   ![Audit](/public/assets/audits.jpeg)
-   ![Audit](/public/assets/contact_with_debts.jpeg)
-   ![Audit](/public/assets/debt_with_pays.jpeg)
