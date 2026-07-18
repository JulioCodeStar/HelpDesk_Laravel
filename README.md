# 🎫 Sistema de Mesa de Ayuda (Helpdesk)

Sistema de gestión de tickets de soporte técnico desarrollado con **Laravel 12**. Permite a los usuarios crear tickets, mantener conversaciones con los agentes de soporte, adjuntar archivos, y hacer seguimiento del estado y la prioridad de cada solicitud.

---

## 📋 Tabla de contenidos

- [Características](#-características)
- [Stack tecnológico](#-stack-tecnológico)
- [Requisitos previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Configuración de la base de datos](#-configuración-de-la-base-de-datos)
- [Ejecución](#-ejecución)
- [Estructura de la base de datos](#-estructura-de-la-base-de-datos)
- [Roles de usuario](#-roles-de-usuario)
- [Comandos útiles](#-comandos-útiles)

---

## ✨ Características

- Gestión completa de tickets de soporte (crear, asignar, dar seguimiento y cerrar).
- Hilo de conversación por ticket entre clientes y agentes.
- Archivos adjuntos a nivel de ticket y a nivel de mensaje individual.
- Catálogos configurables de **estados**, **prioridades** y **categorías**.
- Sistema de **prioridades con tiempo de respuesta objetivo** (SLA).
- **Registro de auditoría** (logs) de las acciones sobre cada ticket.
- Organización por **departamentos**.
- Sección de **preguntas frecuentes (FAQs)**.
- Autenticación integrada con **Laravel Breeze**.
- Roles diferenciados: cliente, agente y administrador.

---

## 🛠 Stack tecnológico

| Componente        | Tecnología                       |
|-------------------|----------------------------------|
| Framework         | Laravel 12                       |
| Lenguaje          | PHP 8.4 (compatible desde 8.2)   |
| Base de datos     | PostgreSQL                       |
| Autenticación     | Laravel Breeze (Blade + Alpine)  |
| Frontend / estilos| Blade + Tailwind CSS + Alpine.js |
| Entorno local     | Laravel Herd                     |

---

## 📦 Requisitos previos

Antes de instalar, asegúrate de tener:

- **PHP** 8.2 o superior (recomendado 8.4).
- **Composer** (gestor de dependencias de PHP).
- **PostgreSQL** en ejecución.
- **Node.js** y **npm** (para compilar los assets del frontend).
- **Laravel Herd** (opcional pero recomendado en Windows/macOS para el servidor local).
- Extensión de PHP para PostgreSQL activa (`pdo_pgsql`).

Verifica tus versiones:

```bash
php -v
composer -V
node -v
php -m | grep pdo_pgsql
```

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/helpdesk-pandanoir.git
cd helpdesk-pandanoir
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de frontend

```bash
npm install
```

### 4. Configurar el archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

---

## 🗄 Configuración de la base de datos

Edita el archivo `.env` con los datos de tu conexión a PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistema_tickets
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

Crea la base de datos en PostgreSQL (si aún no existe) y ejecuta las migraciones:

```bash
php artisan migrate
```

> 💡 Durante el desarrollo, si necesitas reiniciar todo el esquema desde cero, usa
> `php artisan migrate:fresh`. Cuando existan seeders, podrás usar
> `php artisan migrate:fresh --seed`.

---

## ▶️ Ejecución

### Con Laravel Herd (recomendado)

Herd sirve automáticamente el proyecto mediante un dominio `.test`. Solo necesitas "parkear" la carpeta contenedora una vez:

```bash
herd park
```

Luego abre el proyecto en tu navegador:

```
http://helpdesk-pandanoir.test
```

> ⚠️ Con Herd **no** uses `php artisan serve`: Herd ya gestiona el servidor y ese
> comando entrará en conflicto con los puertos.

Deja los assets compilándose en una terminal aparte:

```bash
npm run dev
```

### Sin Herd (servidor de desarrollo de Laravel)

```bash
php artisan serve
npm run dev
```

El proyecto quedará disponible en `http://127.0.0.1:8000`.

---

## 🧩 Estructura de la base de datos

El sistema se organiza en un flujo jerárquico: primero los catálogos base, luego los usuarios, después el ticket como entidad central, y finalmente la actividad que depende de él.

### Catálogos base

#### `departments`
Áreas o departamentos de la organización.

| Campo        | Tipo      | Descripción              |
|--------------|-----------|--------------------------|
| id           | bigint PK | Identificador            |
| name         | varchar   | Nombre (único)           |
| created_at   | timestamp |                          |
| updated_at   | timestamp |                          |

#### `status`
Estados posibles de un ticket (Abierto, En proceso, Cerrado, etc.).

| Campo        | Tipo      | Descripción                     |
|--------------|-----------|---------------------------------|
| id           | bigint PK | Identificador                   |
| name         | varchar   | Nombre del estado               |
| color        | varchar   | Color para la UI (badge)        |
| created_at   | timestamp |                                 |
| updated_at   | timestamp |                                 |

#### `priority`
Prioridades con su tiempo de respuesta objetivo (SLA).

| Campo                   | Tipo      | Descripción                          |
|-------------------------|-----------|--------------------------------------|
| id                      | bigint PK | Identificador                        |
| name                    | varchar   | Nombre de la prioridad (único)       |
| response_time_minutes   | int       | Tiempo de respuesta objetivo (min)   |
| created_at              | timestamp |                                      |
| updated_at              | timestamp |                                      |

#### `categories`
Categorías temáticas de los tickets (Hardware, Software, Redes, etc.).

| Campo        | Tipo      | Descripción              |
|--------------|-----------|--------------------------|
| id           | bigint PK | Identificador            |
| name         | varchar   | Nombre (único)           |
| description  | varchar   | Descripción              |
| created_at   | timestamp |                          |
| updated_at   | timestamp |                          |

### Usuarios

#### `users`
Usuarios del sistema: clientes, agentes y administradores.

| Campo          | Tipo      | Descripción                              |
|----------------|-----------|------------------------------------------|
| id             | bigint PK | Identificador                            |
| name           | varchar   | Nombre                                   |
| email          | varchar   | Correo (único)                           |
| password       | varchar   | Contraseña (hash)                        |
| department_id  | bigint FK | → `departments.id`                       |
| role           | enum      | `cliente`, `agente` o `admin`            |
| created_at     | timestamp |                                          |
| updated_at     | timestamp |                                          |

### Entidad central

#### `tickets`
Cada solicitud de soporte. Aquí converge toda la información.

| Campo         | Tipo      | Descripción                          |
|---------------|-----------|--------------------------------------|
| id            | bigint PK | Identificador                        |
| user_id       | bigint FK | → `users.id` (creador)               |
| assigned_to   | bigint FK | → `users.id` (agente asignado)       |
| subject       | varchar   | Asunto                               |
| description   | text      | Descripción del problema             |
| status_id     | bigint FK | → `status.id`                        |
| priority_id   | bigint FK | → `priority.id`                      |
| category_id   | bigint FK | → `categories.id`                    |
| created_at    | timestamp |                                      |
| updated_at    | timestamp |                                      |
| closed_at     | timestamp | Fecha de cierre (nullable)           |

### Actividad sobre el ticket

#### `ticket_messages`
Hilo de conversación de cada ticket.

| Campo        | Tipo      | Descripción              |
|--------------|-----------|--------------------------|
| id           | bigint PK | Identificador            |
| ticket_id    | bigint FK | → `tickets.id`           |
| user_id      | bigint FK | → `users.id` (autor)     |
| message      | text      | Contenido del mensaje    |
| created_at   | timestamp |                          |
| updated_at   | timestamp |                          |

#### `attachments_tickets`
Archivos adjuntos a nivel del ticket.

| Campo        | Tipo      | Descripción              |
|--------------|-----------|--------------------------|
| id           | bigint PK | Identificador            |
| ticket_id    | bigint FK | → `tickets.id`           |
| file_path    | varchar   | Ruta del archivo         |
| file_type    | varchar   | Tipo de archivo          |
| created_at   | timestamp |                          |
| updated_at   | timestamp |                          |

#### `attachments_messages`
Archivos adjuntos a nivel de mensaje individual.

| Campo             | Tipo      | Descripción                  |
|-------------------|-----------|------------------------------|
| id                | bigint PK | Identificador                |
| ticket_message_id | bigint FK | → `ticket_messages.id`       |
| file_path         | varchar   | Ruta del archivo             |
| file_type         | varchar   | Tipo de archivo              |
| created_at        | timestamp |                              |
| updated_at        | timestamp |                              |

#### `logs`
Auditoría de acciones sobre los tickets.

| Campo        | Tipo      | Descripción              |
|--------------|-----------|--------------------------|
| id           | bigint PK | Identificador            |
| ticket_id    | bigint FK | → `tickets.id`           |
| action       | varchar   | Acción realizada         |
| user_id      | bigint FK | → `users.id`             |
| created_at   | timestamp | (sin `updated_at`)       |

### Independiente

#### `faqs`
Preguntas frecuentes.

| Campo        | Tipo      | Descripción              |
|--------------|-----------|--------------------------|
| id           | bigint PK | Identificador            |
| title        | varchar   | Título                   |
| description  | text      | Contenido                |
| created_at   | timestamp |                          |
| updated_at   | timestamp |                          |

### Resumen de relaciones

```
departments   1 ─── N   users
users         1 ─── N   tickets   (creador → user_id)
users         1 ─── N   tickets   (asignado → assigned_to)
status        1 ─── N   tickets
priority      1 ─── N   tickets
categories    1 ─── N   tickets
tickets       1 ─── N   ticket_messages
tickets       1 ─── N   attachments_tickets
tickets       1 ─── N   logs
ticket_messages 1 ─ N   attachments_messages
users         1 ─── N   ticket_messages
users         1 ─── N   logs
faqs          (independiente)
```

---

## 👥 Roles de usuario

| Rol       | Descripción                                                        |
|-----------|--------------------------------------------------------------------|
| `cliente` | Crea tickets y conversa con los agentes. Rol por defecto.          |
| `agente`  | Atiende tickets asignados, responde y cambia estados.              |
| `admin`   | Acceso completo: gestión de usuarios, catálogos y configuración.   |

> Por defecto, todo usuario registrado desde el formulario público se crea con el
> rol `cliente`.

---

## 🔧 Comandos útiles

```bash
# Migraciones
php artisan migrate                 # Ejecutar migraciones pendientes
php artisan migrate:fresh           # Recrear todas las tablas desde cero
php artisan migrate:fresh --seed    # Recrear y poblar con seeders

# Caché y optimización
php artisan optimize:clear          # Limpiar toda la caché
php artisan config:clear            # Limpiar caché de configuración

# Frontend
npm run dev                         # Compilar assets en modo desarrollo
npm run build                       # Compilar assets para producción
```

---

## 📄 Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).
</file_text>
