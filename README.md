# Proyecto UPTP4 — Sistema de Gestión

Plataforma web para administración de Personas, Proveedores, Bitácora de cambios y Reportes. Construida sobre Laravel 12 con una interfaz Material/Bootstrap, integración con JasperReports (Java 8) y utilidades modernas de frontend (Vite, Tailwind/Bootstrap, Chart.js).

## Funcionalidades

- Gestión de Personas: alta/edición/restauración, validaciones y ubicación por Estado/Municipio/Parroquia (fuente `public/data/venezuela.json`).
- Gestión de Proveedores: alta/edición/restauración, clasificación por tipos, productos/servicios en JSON, contacto comercial y métricas básicas.
- Bitácora de cambios: auditoría por módulo/acción/usuario, detalle de datos anteriores vs nuevos, vista responsiva y formateo claro.
- Reportes: generación de reportes PDF/XLSX con JasperReports (plantillas `.jrxml`), pruebas de entorno Java desde ruta dedicada.
- Dashboard: vista inicial con métricas; endpoint para tipo de cambio vía `ExchangeRateService`.
- Recepciones y Órdenes de Compra: vistas “stub” preparadas para futura implementación.
- Configuración de usuario: actualización de preferencias básicas.
- Autenticación: login/logout/recuperación de contraseña, con verificación de código opcional.
- Roles y permisos: gestión mediante `spatie/laravel-permission`.

## Tecnologías y Lenguajes

- Backend: PHP 8.2, Laravel 12, Eloquent ORM, Form Requests, Middlewares.
- Frontend: Bootstrap 5.3, Material theme, Vite 7, Tailwind 4 (base), Sass, jQuery DataTables, Chart.js + D3/TopoJSON para mapas.
- Livewire 3 para componentes interactivos (cuando aplique).
- Reportes: JasperReports vía `geekcom/phpjasper`, `jasperphp/jasperphp` (requiere Java 8).
- Autorización: `spatie/laravel-permission`.

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+ y npm
- Base de datos (MySQL/MariaDB recomendada)
- Java 8 para JasperReports (configurable por `JAVA_PATH` en `.env`)

## Instalación rápida

1. Clonar el repositorio y entrar al proyecto
2. Configurar entorno:
	- Copiar `.env.example` a `.env`
	- Definir conexión a base de datos y `JAVA_PATH` si corresponde
3. Instalar dependencias y construir assets:

```bash
composer install
php artisan key:generate
php artisan migrate --force
npm install
npm run build
```

Datos de ejemplo (opcional):

```bash
php artisan db:seed
```

Desarrollo en caliente:

```bash
npm run dev
# o
composer run dev
```

Servidor local:

```bash
php artisan serve
```

## Módulos y Rutas principales

- Dashboard: `/dashboard`
- Bitácora: `/dashboard/bitacora`
- Personas: recurso REST `/dashboard/personas` (+ búsqueda/restauración)
- Proveedores: recurso REST `/dashboard/proveedores` (+ búsqueda/restauración)
- Reportes (PDF/XLSX): `/dashboard/reportes/*` y `/dashboard/reportes/generar/{template}/{formato?}`
- Administración de Reportes: `/dashboard/reportes-admin/*`
- Tipo de cambio: `/dashboard/api/exchange-rate`
- Mapa de Venezuela (JSON público): `/geo/ve.json`

## Integración de Reportes (Jasper)

- Requiere Java 8 (JRE/JDK). Validación disponible en `/test-jasper-command`.
- Plantillas `.jrxml` y recursos bajo `storage/app/reports` y `app/Reports/templates`.
- Servicios: `App\Services\JasperService` para orquestar generación.

## Estructura destacada

- `app/Http/Controllers/dashboard/*`: controladores del panel (Personas, Proveedores, Reportes, etc.)
- `resources/views/dashboard/*`: vistas Blade (Material/Bootstrap), modales y tablas (DataTables)
- `public/data/venezuela.json`: catálogo de Estados → Municipios → Parroquias
- `app/Services/*`: servicios (Jasper, ExchangeRate, Notification)
- `database/migrations/*`: esquema (usuarios, permisos, campos de dirección, etc.)
- `database/seeders/*`: datos de ejemplo (usuarios, personas, proveedores)

## Seguridad y Roles

- Implementado con `spatie/laravel-permission`.
- Definición de roles/permissions en seeders y uso en middlewares/controladores.

## Internacionalización

- Idioma base: español (ES).
- Soporte de textos en `resources/lang`.
- DataTables con localización (`public/datatables-i18n-es.json`).

## Pruebas

- PHPUnit (11.x). Ejecutar:

```bash
php artisan test
```

## Consejos y Troubleshooting

- JasperReports: si falla, verifique `JAVA_PATH` en `.env` y que la versión sea 8.
- Assets: si no se ven estilos, ejecute `npm run build` o `npm run dev` y asegure que Vite esté enlazado en la plantilla `layouts/material.blade.php`.
- Migraciones: ante cambios de esquema, ejecute `php artisan migrate` y, si es entorno local, repare con `php artisan migrate:fresh --seed`.

## Capturas de pantalla

- Para mantener el README ligero, sube capturas a `docs/screenshots/` y enlázalas aquí.
- Sugerencias de capturas:
	- Dashboard principal (estadísticas/atajos)
	- Listado y modales de Personas (crear/editar, ubicación VE)
	- Listado y modales de Proveedores (crear/editar/ver)
	- Bitácora (tabla y modal de detalle)
	- Reportes (pantallas de generación y descarga)
- Ejemplo de inclusión (reemplaza por tus archivos):
	- ![Dashboard](docs/screenshots/dashboard.png)
	- ![Personas](docs/screenshots/personas_list.png)
	- ![Detalle Bitácora](docs/screenshots/bitacora_modal.png)

## Roadmap (próximos módulos)

- Recepciones
	- CRUD de recepciones con estados (pendiente, recibido, verificado)
	- Adjuntos y notas, relación con proveedores y órdenes
	- Reporte de recepción (PDF/XLSX)
- Órdenes de Compra (OC)
	- Flujo de creación, aprobación y estado
	- Exportación PDF/CSV y envío a proveedor
	- Integración con recepciones y bitácora
- Inventario (Materias Primas y Repuestos)
	- Catálogo, existencias, movimientos y kardex
	- Alertas por mínimos y rotación
- Reportes ampliados
	- Personas/Proveedores (segmentación y métricas)
	- Compras/Recepciones/Inventario
- Notificaciones
	- Email y mensajería (soportado por `NotificationService`)
	- Avisos de aprobaciones/recepciones/alertas
- Roles y permisos avanzados
	- Perfiles más granulados y auditoría detallada

---

Este README resume el estado actual de la rama `main` y las capacidades principales del sistema.
