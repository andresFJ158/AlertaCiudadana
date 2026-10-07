# Contrato API — Iteración 0

Base local: `http://127.0.0.1:8000/api`.

Todas las respuestas de recursos utilizan la envoltura `data`.

## Categorías

### `GET /categorias`

Devuelve las categorías disponibles.

```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Alumbrado público",
      "slug": "alumbrado-publico",
      "icono": "bi bi-lightbulb",
      "color": "warning"
    }
  ]
}
```

## Incidencias

### `POST /incidencias`

Usa `multipart/form-data` para admitir fotografía.

| Campo | Tipo | Obligatorio | Regla |
|---|---|---|---|
| `categoria_id` | entero | Sí | Debe existir en categorías |
| `descripcion` | texto | Sí | Máximo 1.000 caracteres |
| `latitud` | número | Sí | Entre -90 y 90 |
| `longitud` | número | Sí | Entre -180 y 180 |
| `foto` | archivo | No | JPEG o PNG, máximo 5 MB |

Respuesta `201 Created`:

```json
{
  "data": {
    "id": 123,
    "categoria_id": 1,
    "descripcion": "Luminaria apagada frente a la plaza",
    "latitud": -17.7833,
    "longitud": -63.1821,
    "estado": "reportada",
    "foto_url": null
  }
}
```

Un cuerpo inválido devuelve `422 Unprocessable Entity` con errores por campo y no crea registros.

### `GET /incidencias`

Devuelve `{"data":[]}` cuando no existen incidencias. En esta iteración el listado no está paginado para mantener el contrato simple del cliente móvil.

### `GET /incidencias/{id}`

Devuelve el detalle con la misma estructura. Un identificador inexistente devuelve `404 Not Found`.

## Autenticación

El MVP permite reportes anónimos. La autenticación se reserva para una iteración posterior. El registro está limitado por frecuencia para reducir abuso básico; en producción también se requiere protección en infraestructura.
