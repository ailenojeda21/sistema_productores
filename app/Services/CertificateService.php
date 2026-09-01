<?php

namespace App\Services;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class CertificateService
{
    public const TIPO_COMPROBANTE = 'comprobante';

    public const TIPO_INFORME = 'informe';

    private const TIPOS = [self::TIPO_COMPROBANTE, self::TIPO_INFORME];

    /**
     * Token estable por (tipo, productor): sin fecha, el mismo productor +
     * mismo documento generan siempre el mismo token/QR.
     */
    public static function token(string $tipo, int $productorId): string
    {
        $firma = substr(
            hash_hmac('sha256', $tipo.'|'.$productorId, (string) config('app.key')),
            0,
            32
        );

        return sprintf('%s-%d-%s', $tipo, $productorId, $firma);
    }

    /**
     * @return array{tipo: string, productor_id: int}|null
     */
    public static function verify(string $token): ?array
    {
        $parts = explode('-', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$tipo, $id, $firma] = $parts;

        if (! in_array($tipo, self::TIPOS, true) || ! ctype_digit($id)) {
            return null;
        }

        $esperada = substr(
            hash_hmac('sha256', $tipo.'|'.$id, (string) config('app.key')),
            0,
            32
        );

        if (! hash_equals($esperada, (string) $firma)) {
            return null;
        }

        return ['tipo' => $tipo, 'productor_id' => (int) $id];
    }

    public static function verificationUrl(string $tipo, int $productorId): string
    {
        return route('verificar', ['token' => self::token($tipo, $productorId)]);
    }

    public static function qrDataUri(string $url): string
    {
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'outputBase64' => true,
            'eccLevel' => EccLevel::L,
            'scale' => 5,
        ]);

        $rendered = (new QRCode($options))->render($url);

        if (str_starts_with((string) $rendered, 'data:image')) {
            return (string) $rendered;
        }

        return 'data:image/png;base64,'.base64_encode((string) $rendered);
    }
}
