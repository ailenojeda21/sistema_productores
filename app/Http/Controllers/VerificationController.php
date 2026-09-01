<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CertificateService;

class VerificationController extends Controller
{
    public function show(string $token)
    {
        $datos = CertificateService::verify($token);

        if ($datos === null) {
            return view('verificar', ['valido' => false]);
        }

        $user = User::find($datos['productor_id']);

        if (! $user) {
            return view('verificar', ['valido' => false]);
        }

        return view('verificar', [
            'valido' => true,
            'nombre' => $user->name,
            'tipoDocumento' => $datos['tipo'] === CertificateService::TIPO_COMPROBANTE
                ? 'Comprobante de Registro'
                : 'Informe Detallado',
            'verificadoEl' => now()->format('d/m/Y H:i'),
        ]);
    }
}
