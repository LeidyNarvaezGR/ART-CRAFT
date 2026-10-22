<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function categorias(Request $request): View
    {
        return view('categorias', [
            'productos' => $this->productos('Mochilas', $request->q),
            'categoriaActual' => 'Mochilas',
            'busqueda' => $request->q,
        ]);
    }

    public function bisuteria(Request $request): View
    {
        return view('bisuteria', [
            'productos' => $this->productos('Bisutería', $request->q),
            'categoriaActual' => 'Bisutería',
            'busqueda' => $request->q,
        ]);
    }

    public function ceramica(Request $request): View
    {
        return view('ceramica', [
            'productos' => $this->productos('Cerámica', $request->q),
            'categoriaActual' => 'Cerámica',
            'busqueda' => $request->q,
        ]);
    }

    public function mascaras(Request $request): View
    {
        return view('mascaras', [
            'productos' => $this->productos('Máscaras', $request->q),
            'categoriaActual' => 'Máscaras',
            'busqueda' => $request->q,
        ]);
    }

    /** Busqueda general: revisa todas las categorias por nombre y descripcion. */
    public function busqueda(Request $request): View
    {
        $texto = trim((string) $request->q);
        $terminos = $this->terminos($texto);

        $productos = Producto::orderBy('nombre')->get();

        if ($terminos) {
            $productos = $productos
                ->filter(function (Producto $producto) use ($terminos) {
                    $contenido = $this->normalizar($producto->nombre.' '.$producto->descripcion.' '.$producto->categoria);

                    foreach ($terminos as $termino) {
                        if (str_contains($contenido, $termino)) {
                            return true;
                        }
                    }

                    return false;
                })
                ->values();
        }

        return view('busqueda', [
            'productos' => $productos,
            'categoriaActual' => 'Todas',
            'busqueda' => $texto,
        ]);
    }

    /**
     * Amplia la busqueda: "mochilas" tambien encuentra "Mochila ..." y la
     * categoria "Mochilas", porque el usuario escribe en plural o sin tilde.
     *
     * @return list<string>
     */
    private function terminos(string $texto): array
    {
        if ($texto === '') {
            return [];
        }

        $terminos = [$this->normalizar($texto)];

        foreach (preg_split('/\s+/', $texto) as $palabra) {
            $normal = $this->normalizar(trim($palabra));

            if (mb_strlen($normal) > 3 && str_ends_with($normal, 'es')) {
                $terminos[] = mb_substr($normal, 0, -2);
                $terminos[] = mb_substr($normal, 0, -1);
            } elseif (mb_strlen($normal) > 3 && str_ends_with($normal, 's')) {
                $terminos[] = mb_substr($normal, 0, -1);
            }
        }

        return array_values(array_unique(array_filter($terminos)));
    }

    /** Minusculas y sin tildes, para comparar "mascara" con "Máscara". */
    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower(trim($texto));

        $letras = [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
            'ñ' => 'n', 'ç' => 'c',
        ];

        return strtr($texto, $letras);
    }

    public function mostrarProducto(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-producto', 'categorias');
    }

    public function mostrarBisuteria(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-bisuteria', 'bisuteria');
    }

    public function mostrarCeramica(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-ceramica', 'ceramica');
    }

    public function mostrarMascaras(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-mascaras', 'mascaras');
    }

    /** Entra a /mostrarproducto sin id: manda al primer producto de la categoría. */
    public function mostrarProductoPorDefecto(Request $request): RedirectResponse
    {
        $producto = Producto::deCategoria($request->route('categoria'))->first()
            ?? Producto::query()->first();

        if (! $producto) {
            return redirect()->route('categorias');
        }

        return redirect()->route($request->route('ruta'), $producto);
    }

    private function detalle(Producto $producto, string $vista, string $rutaVolver): View
    {
        return view($vista, [
            'producto' => $producto,
            'rutaVolver' => $rutaVolver,
        ]);
    }

    private function productos(string $categoria, ?string $busqueda = null)
    {
        return Producto::deCategoria($categoria)
            ->when($busqueda, fn ($query) => $query->where('nombre', 'like', '%'.$busqueda.'%'))
            ->get();
    }
}
