# Iteración 0 — Registro de alertas web y API

## Historia

**HU-01 — Registrar alerta desde la web**

Como ciudadano quiero registrar una alerta desde la web para comunicar un problema de mi ciudad.

## Trazabilidad

- EDT: 1.1
- Caso de uso: CU-01
- Responsable: Andrés
- Canal: Web / API
- Rama: `feature/1-web-registro-alertas`
- Destino de integración: `develop`

## Resultado

- [x] Categoría y descripción obligatorias.
- [x] Ubicación manual y GPS con autorización del navegador.
- [x] Coordenadas validadas en servidor.
- [x] Fotografía JPEG o PNG opcional de hasta 5 MB.
- [x] Estado inicial `reportada`.
- [x] Confirmación con el identificador creado.
- [x] Consulta por API de listado y detalle.
- [x] Entradas inválidas no crean alertas.
- [x] Pruebas automatizadas de API y web.
- [x] Panel visual construido con AdminLTE.

## Cómo probar

1. Sigue la instalación descrita en `web/README.md`.
2. Abre `/alertas/crear`.
3. Selecciona una categoría e ingresa descripción y coordenadas; opcionalmente adjunta una imagen.
4. Envía el formulario y verifica el mensaje con el ID.
5. Confirma que la alerta aparece en `/alertas`.
6. Consulta el mismo ID en `/api/incidencias/{id}`.
7. Envía el formulario sin descripción y confirma que no se crea un registro.
8. Ejecuta `php artisan test --compact` desde `web/`.
