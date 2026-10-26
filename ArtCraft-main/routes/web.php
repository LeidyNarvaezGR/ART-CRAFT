<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('/inicio', [InicioController::class, 'index']);

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
Route::get('/pagar', [CarritoController::class, 'pagar'])->name('pagar');

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

Route::get('/admin', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin', [AdminController::class, 'ingresar'])->name('admin.ingresar');
Route::post('/admin/salir', [AdminController::class, 'salir'])->name('admin.salir');

Route::middleware('auth.admin')->group(function () {
    Route::get('/admin/productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::delete('/admin/productos/{producto}', [AdminController::class, 'eliminar'])->name('admin.eliminar');
});
