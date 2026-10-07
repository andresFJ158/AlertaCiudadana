# Alerta Ciudadana

Prototipo para registrar y consultar reportes urbanos. Esta entrega corresponde a una simulación de la **Iteración 0** y cubre la historia **HU-01 — Registrar alerta desde la web**.

## Alcance de la iteración

- Aplicación web en Laravel 13 con AdminLTE 4.
- Formulario ciudadano con categoría, descripción, coordenadas y fotografía opcional.
- Ubicación manual o mediante el permiso de geolocalización del navegador.
- API REST para categorías e incidencias.
- Validación en servidor y estado inicial `reportada`.
- Panel de consulta de alertas.
- Pruebas automatizadas y CI en GitHub Actions.

El cliente móvil queda fuera de esta única iteración. Puede consumir el contrato documentado en [`docs/CONTRATO_API.md`](docs/CONTRATO_API.md) en una entrega posterior.

## Ejecución

Consulta [`web/README.md`](web/README.md) para instalar dependencias, preparar la base de datos y levantar la aplicación.

## Trazabilidad

| Elemento | Referencia |
|---|---|
| EDT | 1.1 |
| Historia | HU-01 |
| Caso de uso | CU-01 |
| Iteración | Iteración 0 |
| Responsable web/API | Andrés |
| Rama | `feature/1-web-registro-alertas` |

Los detalles de alcance, criterios y resultado están en [`docs/ITERACION_0.md`](docs/ITERACION_0.md).
