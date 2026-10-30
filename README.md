# API de Productos - Prueba Backend

API RESTful hecha con Laravel 12 para gestionar productos, divisas y los precios de cada producto en distintas divisas. Documentada con Swagger (OpenAPI 3).

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Crear la base de datos `prueba_begento` en MySQL y revisar las credenciales en `.env`. Luego:

```bash
php artisan migrate --seed
php artisan serve
```

El seeder carga cinco divisas (USD, EUR, COP, MXN, ARS) y cinco productos de ejemplo.

## Documentación

Con el servidor levantado, abrir [http://127.0.0.1:8000/docs](http://127.0.0.1:8000/docs) (Swagger UI). La especificación está en `public/swagger/openapi.json`.

## Seguridad

Todas las rutas necesitan la cabecera `X-API-Key` con el valor de `API_KEY` del `.env`. En Swagger se configura con el botón **Authorize**. Además hay límite de 60 solicitudes por minuto.

## Endpoints

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/products` | Listar productos (paginado, `per_page`) |
| POST | `/api/products` | Crear producto |
| GET | `/api/products/{id}` | Ver producto |
| PUT | `/api/products/{id}` | Actualizar producto |
| DELETE | `/api/products/{id}` | Eliminar producto |
| GET | `/api/products/{id}/prices` | Listar precios del producto |
| POST | `/api/products/{id}/prices` | Registrar precio en otra divisa |
| GET | `/api/currencies` | Listar divisas (para conocer los ids) |

Ejemplo:

```bash
curl -X POST http://127.0.0.1:8000/api/products \
  -H "X-API-Key: cambia-esta-clave" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name":"Vitamina C","description":"Caja x 30","price":25.5,"currency_id":1,"tax_cost":4.8,"manufacturing_cost":9.99}'
```

## Reglas y decisiones

- Los importes son `DECIMAL`.
- `exchange_rate` es la cantidad de unidades de la divisa por 1 USD (solo se almacena, no hay conversión).
- Un producto solo puede tener un precio por divisa, y no en su divisa base (esa ya está en el producto).
- Al eliminar un producto se eliminan sus precios.
- Todas las respuestas, también los errores, son JSON.

## Pruebas

```bash
php artisan test
```

Usan SQLite en memoria, no tocan la base de datos real.
