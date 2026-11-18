<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function mensaje(Request $request)
    {
        $datos = $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        try {
            $api = rtrim(
                config('services.artcraft_api.url', 'http://127.0.0.1:8001/api'),
                '/'
            );

            $respuesta = Http::timeout(35)
                ->acceptJson()
                ->post($api . '/chatbot/mensaje', [
                    'mensaje' => $datos['mensaje'],
                ]);

            if (! $respuesta->successful()) {
                return response()->json([
                    'ok' => false,
                    'error' => 'No se pudo obtener respuesta del chatbot.',
                ], 502);
            }

            return response()->json([
                'ok' => true,
                'respuesta' => $respuesta->json('respuesta')
                    ?? 'No pude responder tu pregunta.',
            ]);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'No fue posible conectar con el asistente.',
            ], 502);
        }
    }
}
