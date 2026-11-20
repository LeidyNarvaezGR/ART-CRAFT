<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;


Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('/inicio', [InicioController::class, 'index']);
Route::get('/api/catalogo', [InicioController::class, 'catalogo'])->name('api.catalogo');

Route::get('/categorias', [CatalogoController::class, 'categorias'])->name('categorias');
Route::get('/bisuteria', [CatalogoController::class, 'bisuteria'])->name('bisuteria');
Route::get('/ceramica', [CatalogoController::class, 'ceramica'])->name('ceramica');
Route::get('/mascaras', [CatalogoController::class, 'mascaras'])->name('mascaras');
Route::get('/buscar', [CatalogoController::class, 'busqueda'])->name('busqueda');

Route::get('/mostrarproducto/{producto}', [CatalogoController::class, 'mostrarProducto'])->name('mostrar-producto');
Route::get('/mostrarbisuteria/{producto}', [CatalogoController::class, 'mostrarBisuteria'])->name('mostrar-bisuteria');
Route::get('/mostrarceramica/{producto}', [CatalogoController::class, 'mostrarCeramica'])->name('mostrar-ceramica');
Route::get('/mostrarmascaras/{producto}', [CatalogoController::class, 'mostrarMascaras'])->name('mostrar-mascaras');

Route::get('/mostrarproducto', [CatalogoController::class, 'mostrarProductoPorDefecto'])->defaults('categoria', 'Mochilas')->defaults('ruta', 'mostrar-producto');
Route::get('/mostrarbisuteria', [CatalogoController::class, 'mostrarProductoPorDefecto'])->defaults('categoria', 'Bisutería')->defaults('ruta', 'mostrar-bisuteria');
Route::get('/mostrarceramica', [CatalogoController::class, 'mostrarProductoPorDefecto'])->defaults('categoria', 'Cerámica')->defaults('ruta', 'mostrar-ceramica');
Route::get('/mostrarmascaras', [CatalogoController::class, 'mostrarProductoPorDefecto'])->defaults('categoria', 'Máscaras')->defaults('ruta', 'mostrar-mascaras');

Route::get('/carritoartesano', [CarritoController::class, 'index'])->name('carrito');
Route::post('/carritoartesano/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carritoartesano/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('/carritoartesano/quitar', [CarritoController::class, 'quitar'])->name('carrito.quitar');
Route::post('/carritoartesano/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
// Mostrar el formulario de pago
Route::get('/pagar', [PagoController::class, 'index'])
    ->name('pagar');

// Recibir los datos de compra desde el frontend
Route::post('/pago/crear', [PagoController::class, 'crear'])
    ->name('pago.crear');

// Mostrar el resultado de la compra
Route::get('/pago/respuesta/{orden}', [PagoController::class, 'respuesta'])
    ->name('pago.respuesta');

// Consultar las órdenes del usuario
Route::get('/mis-ordenes', [PagoController::class, 'misOrdenes'])
    ->name('mis.ordenes');
    
Route::get('/acceso', [AuthController::class, 'acceso'])->name('acceso');
Route::post('/acceso', [AuthController::class, 'login'])->name('login');
Route::get('/registrousuario', [AuthController::class, 'registro'])->name('registro');
Route::post('/registrousuario', [AuthController::class, 'registrar'])->name('registrar');
Route::post('/salir', [AuthController::class, 'salir'])->name('salir');

Route::get('/perfilartesano', [ArtesanoController::class, 'perfil'])->name('perfil-artesano');
Route::get('/miperfil', [PerfilController::class, 'cliente'])->name('mi-perfil');
Route::get('/subirproducto', [ArtesanoController::class, 'subirProducto'])->name('subir-producto');
Route::post('/subirproducto', [ArtesanoController::class, 'guardarProducto'])->name('guardar-producto');

Route::get('/bloghistoria', [ContenidoController::class, 'blog'])->name('blog');
Route::get('/contacto', [ContenidoController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [ContenidoController::class, 'enviarMensaje'])->name('contacto.enviar');
Route::get('/sobrenosotros', [ContenidoController::class, 'sobreNosotros'])->name('sobre-nosotros');

Route::get('/notificaciones', [NotificacionesController::class, 'index'])->name('notificaciones');
Route::post('/notificaciones/leer', [NotificacionesController::class, 'marcarLeidas'])->name('notificaciones.leer');


// ADMINISTRACIÓN ART CRAFT

// Mostrar inicio de sesión
Route::get('/admin/login', [AdminController::class, 'login'])
    ->name('admin.login');

// Procesar inicio de sesión
Route::post('/admin/login', [AdminController::class, 'ingresar'])
    ->name('admin.ingresar');

// Rutas exclusivas del administrador
Route::middleware('auth.admin')->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Perfil
    Route::get('/admin/perfil', function () {
        return view('admin.perfil');
    })->name('admin.perfil');

    // Productos
    Route::get('/admin/productos', [AdminController::class, 'productos'])
        ->name('admin.productos');

    // Eliminar productos
    Route::delete('/admin/productos/{producto}', [AdminController::class, 'eliminar'])
        ->name('admin.eliminar');

    // Cerrar sesión
    Route::post('/admin/salir', [AdminController::class, 'salir'])
        ->name('admin.salir');
});

// Chatbot
Route::post('/chatbot/mensaje', [ChatbotController::class, 'mensaje'])
    ->middleware('throttle:20,1')
    ->name('chatbot.mensaje');