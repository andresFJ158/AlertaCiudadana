# Aplicación web — Alerta Ciudadana

Aplicación Laravel 13 con AdminLTE 4 y SQLite para la Iteración 0.

## Requisitos

- PHP 8.5
- Composer 2
- Extensión SQLite de PHP

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Abre `http://127.0.0.1:8000`. El formulario se encuentra en `/alertas/crear` y el panel en `/alertas`.

## Pruebas

```bash
php artisan test --compact
```

## API

Las rutas públicas son:

- `GET /api/categorias`
- `GET /api/incidencias`
- `POST /api/incidencias`
- `GET /api/incidencias/{id}`

El contrato completo se encuentra en [`../docs/CONTRATO_API.md`](../docs/CONTRATO_API.md).

## Archivos locales

`.env`, `vendor/`, la base SQLite y las fotografías almacenadas no se versionan. Nunca agregues credenciales al repositorio.
