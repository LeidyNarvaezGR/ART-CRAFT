<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WompiService
{
    /** Estado de una transacción aprobada en Wompi. */
    public const ESTADO_APROBADO = 'APPROVED';
    /** Estado de una transacción declinada en Wompi. */
    public const ESTADO_DECLINADO = 'DECLINED';
    /** Estado de una transacción con error en Wompi. */
    public const ESTADO_ERROR = 'ERROR';

    private string $publicKey;
    private string $privateKey;
    private string $integritySecret;
    private string $baseUrl;
    private string $responseUrl;
    private string $confirmationUrl;

    public function __construct()
    {
        $config = config('services.wompi');

        $this->publicKey = $config['public_key'] ?? '';
        $this->privateKey = $config['private_key'] ?? '';
        $this->integritySecret = $config['integrity_secret'] ?? '';
        $this->baseUrl = rtrim($config['base_url'] ?? 'https://sandbox.wompi.co/v1', '/');
        $this->responseUrl = $config['response_url'] ?? '';
        $this->confirmationUrl = $config['confirmation_url'] ?? '';
    }

    /** Indica si Wompi está configurado con las credenciales necesarias. */
    public function estaConfigurado(): bool
    {
        return ! empty($this->publicKey)
            && ! empty($this->privateKey)
            && ! empty($this->integritySecret);
    }

    private function cliente(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Authorization' => 'Bearer '.$this->privateKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
    }

    /**
     * Crea una transacción de pago en Wompi.
     *
     * @param  array  $datos ['amount_in_cents', 'customer_email', 'reference', 'payment_method', 'payment_method_details'?]
     * @return array ['ok' => bool, 'transaction' => array|null, 'error' => string|null]
     */
    public function crearTransaccion(array $datos): array
    {
        if (! $this->estaConfigurado()) {
            return [
                'ok' => false,
                'transaction' => null,
                'error' => 'Wompi no está configurado. Define WOMPI_PUBLIC_KEY, WOMPI_PRIVATE_KEY y WOMPI_INTEGRITY_SECRET en .env',
            ];
        }

        $payload = array_merge([
            'amount_in_cents' => (int) $datos['amount_in_cents'],
            'currency' => 'COP',
            'customer_email' => $datos['customer_email'] ?? 'cliente@artcraft.test',
            'reference' => $datos['reference'],
            'payment_method' => $datos['payment_method'] ?? 'CARD',
            'confirmation_url' => $this->confirmationUrl,
        ], $datos['payment_method_details'] ?? []);

        try {
            $respuesta = $this->cliente()->post('/transactions', $payload);

            $datosRespuesta = $respuesta->json();

            if ($respuesta->successful() && isset($datosRespuesta['data'])) {
                $transaccion = $datosRespuesta['data'];

                return [
                    'ok' => true,
                    'transaction' => $transaccion,
                    'error' => null,
                ];
            }

            return [
                'ok' => false,
                'transaction' => $datosRespuesta['data'] ?? null,
                'error' => $datosRespuesta['message'] ?? 'Error al crear la transacción en Wompi',
            ];
        } catch (\Throwable $e) {
            Log::error('Wompi crearTransaccion error: '.$e->getMessage());

            return [
                'ok' => false,
                'transaction' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Consulta el estado de una transacción por su ID de Wompi.
     */
    public function obtenerTransaccion(string $transactionId): array
    {
        if (! $this->estaConfigurado()) {
            return ['ok' => false, 'transaction' => null, 'error' => 'Wompi no configurado'];
        }

        try {
            $respuesta = $this->cliente()->get('/transactions/'.$transactionId);

            if ($respuesta->successful() && isset($respuesta->json()['data'])) {
                return ['ok' => true, 'transaction' => $respuesta->json()['data'], 'error' => null];
            }

            return [
                'ok' => false,
                'transaction' => null,
                'error' => $respuesta->json()['message'] ?? 'Error al consultar la transacción',
            ];
        } catch (\Throwable $e) {
            Log::error('Wompi obtenerTransaccion error: '.$e->getMessage());

            return ['ok' => false, 'transaction' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Valida la firma de un webhook de Wompi.
     *
     * Wompi firma con HMAC-SHA256 sobre: reference + status + amount_in_cents
     * usando el integrity_secret.
     */
    public function validarWebhook(array $cuerpo, string $firma): bool
    {
        if (empty($this->integritySecret)) {
            return false;
        }

        $data = $cuerpo['data'] ?? $cuerpo;

        $referencia = $data['reference'] ?? '';
        $estado = $data['status'] ?? '';
        $monto = $data['amount_in_cents'] ?? 0;

        $mensaje = $referencia.$estado.$monto;

        $firmaEsperada = hash_hmac('sha256', $mensaje, $this->integritySecret);

        return hash_equals($firmaEsperada, $firma);
    }

    /**
     * Devuelve la URL de checkout de Wompi para redirigir al cliente.
     * Se usa cuando se quiere que Wompi capture los datos de la tarjeta en su página segura.
     */
    public function urlCheckout(string $referencia, int $montoCentavos, string $email): string
    {
        $parametros = http_build_query([
            'publicKey' => $this->publicKey,
            'amountInCents' => $montoCentavos,
            'currency' => 'COP',
            'reference' => $referencia,
            'email' => $email,
            'responseUrl' => $this->responseUrl,
        ]);

        return 'https://checkout.wompi.co/?'.$parametros;
    }
}
