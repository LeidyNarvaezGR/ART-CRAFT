<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    private \Cloudinary\Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = $this->crearInstancia();
    }

    private function crearInstancia(): \Cloudinary\Cloudinary
    {
        $config = config('services.cloudinary');

        if (empty($config['cloud_name']) || empty($config['api_key']) || empty($config['api_secret'])) {
            // Cloudinary no configurado: instancia vacía (subidas devolverán null).
            return new \Cloudinary\Cloudinary();
        }

        return new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => $config['cloud_name'],
                'api_key' => $config['api_key'],
                'api_secret' => $config['api_secret'],
                'secure' => true,
            ],
        ]);
    }

    /** Indica si Cloudinary está configurado y listo para usar. */
    public function estaConfigurado(): bool
    {
        $config = config('services.cloudinary');

        return ! empty($config['cloud_name'])
            && ! empty($config['api_key'])
            && ! empty($config['api_secret']);
    }

    /**
     * Sube una imagen a Cloudinary.
     *
     * @param  UploadedFile|string  $file Ruta del archivo o instancia de UploadedFile
     * @param  string  $folder Carpeta de destino en Cloudinary
     * @return array{public_id: string, secure_url: string, version: int}|null
     */
    public function subirImagen($file, string $folder = 'artcraft/productos'): ?array
    {
        if (! $this->estaConfigurado()) {
            Log::warning('Cloudinary no está configurado. La imagen se gestionará localmente.');

            return null;
        }

        try {
            $respuesta = $this->cloudinary->uploadApi()->upload($file, [
                'folder' => $folder,
                'resource_type' => 'image',
                'transformation' => [
                    'quality' => 'auto:good',
                    'fetch_format' => 'auto',
                ],
            ]);

            // ApiResponse extiende ArrayObject: se accede como array u objeto.
            $publicId = $respuesta['public_id'] ?? null;
            $secureUrl = $respuesta['secure_url'] ?? null;
            $version = $respuesta['version'] ?? null;

            if (! $publicId || ! $secureUrl) {
                Log::error('Cloudinary respondió sin public_id ni secure_url.');

                return null;
            }

            return [
                'public_id' => $publicId,
                'secure_url' => $secureUrl,
                'version' => $version,
            ];
        } catch (\Throwable $e) {
            Log::error('Error subiendo imagen a Cloudinary: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Genera la URL de entrega de una imagen ya subida.
     */
    public function obtenerUrl(string $publicId, array $transformaciones = []): string
    {
        $config = config('services.cloudinary');

        if (! $this->estaConfigurado() || empty($config['cloud_name'])) {
            return asset('imagenes/sin-imagen.png');
        }

        $transformacion = ! empty($transformaciones)
            ? implode('/', $transformaciones).'/'
            : '';

        return 'https://res.cloudinary.com/'.$config['cloud_name'].
            '/image/upload/'.$transformacion.$publicId;
    }

    /** Elimina una imagen de Cloudinary por su public_id. */
    public function eliminarImagen(string $publicId): bool
    {
        if (! $this->estaConfigurado()) {
            return false;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId);

            return true;
        } catch (\Throwable $e) {
            Log::error('Error eliminando imagen de Cloudinary: '.$e->getMessage());

            return false;
        }
    }
}
