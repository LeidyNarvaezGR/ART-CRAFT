<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Services\CloudinaryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArtesanoController extends Controller
{
    public function __construct(
        private CloudinaryService $cloudinary,
    ) {
    }

    public function perfil(): View
    {
        $usuario = session('usuario');
        $esArtesano = is_array($usuario) && ($usuario['rol'] ?? 'cliente') === 'artesano';

        return view('perfil-artesano', [
            'esArtesano' => $esArtesano,
            'nombre' => $esArtesano ? $usuario['nombre'] : 'María Gómez',
            'email' => $esArtesano ? ($usuario['email'] ?? null) : null,
        ]);
    }

    public function subirProducto(): View
    {
        return view('subir-producto', [
            'cloudinaryConfigurado' => $this->cloudinary->estaConfigurado(),
        ]);
    }

    public function guardarProducto(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'categoria' => ['required', 'string', 'max:60'],
            'precio' => ['required', 'numeric', 'min:1'],
            'descripcion' => ['required', 'string', 'max:1000'],
            'imagen' => ['nullable', 'file', 'image', 'max:4096'], // hasta 4 MB
        ]);

        $imagenUrl = null;
        $publicId = null;
        $imagenNombre = $request->file('imagen') ? $request->file('imagen')->getClientOriginalName() : null;

        // Si hay archivo y Cloudinary está configurado, subir a la nube.
        if ($request->hasFile('imagen') && $this->cloudinary->estaConfigurado()) {
            $subida = $this->cloudinary->subirImagen(
                $request->file('imagen'),
                'artcraft/productos'
            );

            if ($subida) {
                $imagenUrl = $subida['secure_url'];
                $publicId = $subida['public_id'];
            }
        }

        Producto::create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'],
            'categoria' => $datos['categoria'],
            'precio' => (int) $datos['precio'],
            'imagen' => $imagenNombre,
            'cloudinary_public_id' => $publicId,
            'cloudinary_imagen_url' => $imagenUrl,
            'ruta_detalle' => 'mostrar-producto',
            'destacado' => false,
        ]);

        return back()->with('mensaje', 'Producto '.$datos['nombre'].' registrado correctamente.');
    }
}
