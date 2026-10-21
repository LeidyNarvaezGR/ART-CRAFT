# Art Craft

Marketplace de artesanías colombianas desarrollado con **Laravel 12** (backend + Blade) y **React 19** (portada).

El proyecto vive dentro de XAMPP (`C:\xampp\htdocs\artcraft`) y usa la base de datos MySQL `artcraft`.
La navegación entre páginas no recarga el navegador (ver la sección 8).

---

## 1. Requisitos

| Herramienta | Versión |
|---|---|
| PHP | 8.2 o superior (la de XAMPP) |
| Composer | 2.x |
| Node.js | 18 o superior |
| MySQL | el de XAMPP (puerto 3306, usuario `root` sin contraseña) |

## 2. Puesta en marcha (primera vez)

```bash
composer install
copy .env.example .env          # en Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed      # crea las tablas y los 24 productos de ejemplo
npm install
npm run build                   # compila los assets (incluye la portada en React)
```

La base de datos `artcraft` se puede crear desde phpMyAdmin o con:

```sql
CREATE DATABASE artcraft CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 3. Cómo abrir la página

**Opción A — XAMPP (la que se usa en la presentación)**

1. Abre el **Panel de Control de XAMPP**.
2. Inicia **Apache** y **MySQL**.
3. Entra en <http://localhost/artcraft/public>.

**Opción B — Servidor de Laravel**

```bash
php artisan serve
```

Entra en <http://127.0.0.1:8000>.

> Las rutas de los assets se generan con `URL::forceRootUrl()` (`app/Providers/AppServiceProvider.php`), por eso el proyecto funciona
> en la subcarpeta de XAMPP y también con `artisan serve` sin cambiar nada.

## 4. Desarrollo con recarga automática

```bash
npm run dev      # servidor de Vite con Hot Module Replacement
php artisan serve
```

Al terminar la presentación hay que dejar los assets compilados otra vez: `npm run build`.

---

## 5. Estructura del proyecto

```
app/
  Http/Controllers/
    InicioController.php      -> portada (React)
    CatalogoController.php    -> categorías y fichas de producto
    ArtesanoController.php    -> perfil del artesano y subir producto
    CarritoController.php      -> carrito (sesión) y página de pago
    AuthController.php        -> acceso y registro
    ContenidoController.php   -> blog, contacto y nosotros
  Models/Producto.php         -> modelo Eloquent del catálogo

database/
  migrations/..._create_productos_table.php
  seeders/ProductoSeeder.php  -> 24 productos (mochilas, bisutería, cerámica, máscaras)

resources/
  views/
    layouts/app.blade.php     -> layout común (@yield, @stack, @include)
    partials/
      header.blade.php        -> barra de navegación compartida
      footer.blade.php        -> pie de página compartido
      tabs-categorias.blade.php
      grid-productos.blade.php-> grilla que recorre $productos
      detalle-producto.blade.php-> ficha compartida por las cuatro categorías
    inicio.blade.php          -> contenedor de React (#artcraft-inicio)
    <paginas>.blade.php       -> acceso, carrito, ceramic, mostrar-*, etc.
  js/
    app.js                     -> entrada común: axios, Turbo y menú lateral
    menu.js                    -> abre/cierra el menú lateral (clic, no solo hover)
    inicio.jsx                 -> punto de entrada de la portada en React
    components/
      App.jsx                  -> une los componentes y reparte el estado
      Navbar.jsx               -> logo, buscador, menú y contador del carrito
      Hero.jsx                 -> banner principal
      Destacados.jsx           -> filtros por categoría y grilla de productos
      ProductoCard.jsx         -> tarjeta de un producto
      Footer.jsx               -> pie de página
    hooks/
      useCarrito.js            -> carrito: ids de la sesión + total y alta con fetch
      useFiltroProductos.js    -> búsqueda + categoría con useMemo
    utils/formato.js           -> precio con Intl.NumberFormat('es-CO')
  css/app.css                 -> Tailwind (utilidades, sin preflight)

public/
  css/    -> estilos (base.css, inicio.css, componentes.css, css de cada página)
  imagenes/-> imágenes del sitio
  build/  -> assets compilados por Vite
```

## 6. Rutas del sitio

| Ruta | Vista | Nombre de la ruta |
|---|---|---|
| `/` , `/inicio` | Portada en React | `inicio` |
| `/categorias` | Catálogo de mochilas | `categorias` |
| `/bisuteria` | Catálogo de bisutería | `bisuteria` |
| `/ceramica` | Catálogo de cerámica | `ceramica` |
| `/mascaras` | Catálogo de máscaras | `mascaras` |
| `/mostrarproducto/{id}` | Ficha del producto indicado | `mostrar-producto` |
| `/mostrarbisuteria/{id}` | Ficha de un producto de bisutería | `mostrar-bisuteria` |
| `/mostrarceramica/{id}` | Ficha de un producto de cerámica | `mostrar-ceramica` |
| `/mostrarmascaras/{id}` | Ficha de un producto de máscaras | `mostrar-mascaras` |
| `/carritoartesano` | Carrito | `carrito` |
| `/carritoartesano/agregar` | Agrega al carrito (POST) | `carrito.agregar` |
| `/carritoartesano/actualizar` | Cambia la cantidad (POST) | `carrito.actualizar` |
| `/carritoartesano/quitar` | Quita un producto (POST) | `carrito.quitar` |
| `/carritoartesano/vaciar` | Vacía el carrito (POST) | `carrito.vaciar` |
| `/pagar` | Pago | `pagar` |
| `/acceso` | Iniciar sesión | `acceso` |
| `/registrousuario` | Registro | `registro` |
| `/perfilartesano` | Perfil del artesano | `perfil-artesano` |
| `/subirproducto` | Subir producto | `subir-producto` |
| `/bloghistoria` | Blog e historias | `blog` |
| `/contacto` | Contacto y soporte | `contacto` |
| `/sobrenosotros` | Sobre nosotros | `sobre-nosotros` |
| `/notificaciones` | Notificaciones | `notificaciones` |
| `/notificaciones/leer` | Marca todas como leídas (POST) | `notificaciones.leer` |
| `/admin` | Inicio de sesión del administrador | `admin.login` |
| `/admin` (POST) | Valida las credenciales del admin | `admin.ingresar` |
| `/admin/salir` | Cierra la sesión del admin (POST) | `admin.salir` |
| `/admin/productos` | Panel para borrar productos | `admin.productos` |
| `/admin/productos/{id}` | Borra el producto (DELETE) | `admin.eliminar` |

Todas las páginas se enlazan con `route('nombre')`; ya no existen archivos `.html`.

## 6.1 Fichas de producto

Cada ficha recibe el **id** del producto que se quiere ver (`/mostrarproducto/5`), y las cuatro
vistas `mostrar-*.blade.php` solo delegan en `partials/detalle-producto.blade.php`. Así la imagen,
el nombre y el precio siempre corresponden al producto del enlace, sin importar desde qué categoría
se abrió. Si alguien entra a `/mostrarproducto` sin id, la ruta redirige al primer producto de la
categoría.

## 6.2 Carrito

El carrito vive en la sesión (`session('carrito')`), así que la portada en React y las páginas Blade
muestran el mismo pedido:

- `POST /carritoartesano/agregar` — recibe `producto_id` y `cantidad`; con `ir_a=pagar` responde
  mandando a la pantalla de pago («Comprar ahora»).
- `POST /carritoartesano/actualizar`, `quitar` y `vaciar` — modifican el carrito.
- `/carritoartesano` y `/pagar` calculan solos el subtotal, el envío ($ 10.000 de la demo) y el total.

`useCarrito` (React) recibe los ids que ya tiene la sesión en `data-carrito-ids` y, al agregar un
producto desde la portada, los guarda con un `fetch` a `carrito.agregar`.

## 6.3 Administrador y notificaciones

El menú lateral incluye **Administrador** (`/admin`). Con la cuenta demo
(`admin@artcraft.com` / `admin12345`) se entra al panel `/admin/productos`, que lista el catálogo y
permite **borrar productos** con `DELETE /admin/productos/{id}`. El acceso lo controla el middleware
`auth.admin` (`app/Http/Middleware/EnsureAdmin.php`), que exige una cuenta con `rol = admin`.

La campana del encabezado lleva a `/notificaciones`, que arma el aviso de los últimos productos
publicados, el estado del carrito y mensajes de ejemplo, y marca todo como leído con
`POST /notificaciones/leer` (el contador se guarda en la sesión).

## 7. La portada en React

`InicioController` consulta los productos marcados como `destacado` y se los entrega a la vista
como JSON (`data-destacados`, `data-categorias`, `data-urls`, `data-carrito-ids`). `resources/js/inicio.jsx` monta
React en `#artcraft-inicio` y la interfaz se separa en componentes dentro de `resources/js/components/`:

- `App` — une todo y reparte el estado.
- `Navbar` — logo, buscador (filtra los destacados en vivo), menú lateral y contador del carrito.
- `Hero` — banner principal.
- `Destacados` — filtros por categoría y grilla de productos.
- `ProductoCard` — tarjeta de un producto (imagen cuadrada, precio y botones).
- `Footer` — pie de página con redes y métodos de pago.

El estado vive en dos hooks reutilizables: `useCarrito` (lo que hay en el carrito, sincronizado con
la sesión) y `useFiltroProductos` (búsqueda y categoría, el filtrado va en un `useMemo`).

## 8. Navegación sin recargar la página

El proyecto usa **Turbo (Hotwire)**, que se activa en `resources/js/app.js`. Al hacer clic en
cualquier enlace interno se pide solo el HTML de la página nueva, se reemplaza el contenido y se
cambia el `<title>`, **sin recargar el navegador completo** (y el botón «atrás» es instantáneo).

Como el bundle de React se carga una sola vez, `inicio.jsx` se monta de nuevo cuando Turbo
termina de traer la portada:

```js
document.addEventListener('turbo:load', montar);
document.addEventListener('turbo:before-cache', desmontar);
```

Para que un enlace se comporte de forma normal se marca con `data-turbo="false"`; así los botones
y enlaces que son `href="#"` (redes sociales, métodos de pago, notificaciones, «Eliminar» del
carrito) no cambian la URL ni disparan una visita.

Cada página tiene su propio CSS (por ejemplo `inicio.css` o `perfil-artesano.css`) cargado con
`@push('estilos')`. Como Turbo solo elimina las hojas marcadas como **dinámicas**, todas van con
`data-turbo-track="dynamic"`: así al cambiar de página se quita el CSS anterior y no se «desorganiza»
el diseño con los estilos de la vista que dejamos atrás.

## 9. Formularios

Los cuatro formularios reales (acceso, registro, contacto y subir producto) reciben los datos,
**validan en el servidor** y vuelven a la misma página con un mensaje:

- `POST /acceso` y `POST /registrousuario` → `AuthController`
- `POST /contacto` → `ContenidoController@enviarMensaje`
- `POST /subirproducto` → `ArtesanoController@guardarProducto`

Los mensajes de validación están en español (`lang/es/validation.php`) y cada vista muestra los
errores con `@if ($errors->any())`. Los datos de la demo no se guardan: el login es de ejemplo y el
botón «Realizar el pedido» es solo visual (el carrito sí funciona de verdad).

## 9.1 Pruebas

```bash
php artisan test
```

Hay pruebas de la portada, de las fichas de producto (que cada id muestre su propio producto), del
carrito (agregar, cambiar cantidad, quitar, vaciar y el total que se ve en `/pagar`), del panel de
administrador (ingreso, borrado de productos y bloqueo a quien no es admin) y de las notificaciones.
Corre sobre SQLite en memoria, así que no tocan la base de datos de la demostración.

## 10. Base de datos

La tabla `productos` guarda: `nombre`, `descripcion`, `categoria`, `precio`, `imagen`, `ruta_detalle`
y `destacado`. La tabla `users` incluye `rol` (`artesano` por defecto; `admin` da acceso al panel).
Para recargar los datos de ejemplo (incluida la cuenta `admin@artcraft.com` / `admin12345`):

```bash
php artisan migrate:fresh --seed
```

## 11. Problemas frecuentes

| Síntoma | Solución |
|---|---|
| Pantalla en blanco con «Base de datos» | Inicia MySQL en XAMPP y ejecuta `php artisan migrate --seed`. |
| No carga el CSS/JS (página sin estilos) | Ejecuta `npm run build`. |
| Cambiaste React y no se ve | `npm run build` otra vez, o usa `npm run dev`. |
| La portada se ve sin estilos al=redimensionar | Haz `Ctrl+F5` para descartar la caché del navegador. |
| `419 Page Expired` al enviar un formulario | Los formularios ya llevan `@csrf`; no edites el token a mano. |
