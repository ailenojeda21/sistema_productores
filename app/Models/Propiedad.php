<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $table = 'propiedades';

    public const DISTRITOS = [
        'costa-de-araujo' => 'Costa de Araujo',
        'el-carmen' => 'El Carmen',
        'el-chilcal' => 'El Chilcal',
        'el-plumero' => 'El Plumero',
        'el-vergel' => 'El Vergel',
        'gustavo-andre' => 'Gustavo André',
        'jocoli' => 'Jocolí',
        'jocoli-viejo' => 'Jocolí Viejo',
        'la-asuncion' => 'La Asunción',
        'la-holanda' => 'La Holanda',
        'la-palmera' => 'La Palmera',
        'la-pega' => 'La Pega',
        'las-violetas' => 'Las Violetas',
        'lagunas-del-rosario' => 'Lagunas del Rosario',
        'paramillo' => 'Paramillo',
        'san-francisco' => 'San Francisco',
        'san-jose' => 'San José',
        'san-miguel' => 'San Miguel',
        'tres-de-mayo' => 'Tres de Mayo',
        'villa-tulumaya' => 'Villa Tulumaya',
        'oscar-mendoza' => 'Oscar Mendoza',
    ];

    public const TIPO_DERECHO_RIEGO = [
        'Subterráneo' => 'Subterráneo',
        'Superficial' => 'Superficial',
        'Ambos' => 'Ambos',
    ];

    public const TIPO_TENENCIA = [
        'propietario' => 'Propietario',
        'arrendatario' => 'Arrendatario',
        'otros' => 'Otro',
    ];

    public static function getDistritosForForm(): array
    {
        return self::DISTRITOS;
    }

    public static function getTipoDerechoRiegoForForm(): array
    {
        return self::TIPO_DERECHO_RIEGO;
    }

    public static function getTipoTenenciaForForm(): array
    {
        return self::TIPO_TENENCIA;
    }

    public function getDistritoLabelAttribute(): string
    {
        return self::DISTRITOS[$this->distrito] ?? ucwords(str_replace('-', ' ', $this->distrito));
    }

    public function getTipoTenenciaLabelAttribute(): string
    {
        return self::TIPO_TENENCIA[$this->tipo_tenencia] ?? $this->tipo_tenencia;
    }

    public function getTipoDerechoRiegoLabelAttribute(): string
    {
        return self::TIPO_DERECHO_RIEGO[$this->tipo_derecho_riego] ?? $this->tipo_derecho_riego;
    }

    // Permitir asignación masiva de todos los campos de la tabla
    protected $fillable = [
        'usuario_id',
        'calle',
        'numeracion',
        'distrito',
        'hectareas',
        'derecho_riego',
        'tipo_derecho_riego',
        'rut',
        'rut_valor',
        'rut_archivo',
        'lat',
        'lng',
        'hectareas_malla',
        'cierre_perimetral',
        'malla',
        'tipo_tenencia',
        'especificar_tenencia',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    /**
     * 🔥 NUEVO: Dirección completa (accessor)
     */
    public function getDireccionCompletaAttribute()
    {
        // Limpiar calle: quitar guiones y capitalizar
        $calle = $this->calle
            ? ucwords(str_replace('-', ' ', $this->calle))
            : '';

        $numero = $this->numeracion ?? '';

        // Limpiar distrito: quitar guiones y capitalizar
        $distrito = $this->distrito
            ? ucwords(str_replace('-', ' ', $this->distrito))
            : null;

        $calleNumero = trim("{$calle} {$numero}");

        if ($distrito) {
            return "{$distrito}, {$calleNumero}";
        }

        return $calleNumero ?: 'Sin dirección';
    }

    /**
     * Relación: Una propiedad pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación: Una propiedad tiene muchos archivos
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'propiedad_id');
    }

    /**
     * Relación: Una propiedad tiene una maquinaria
     */
    public function maquinaria()
    {
        return $this->hasOne(Maquinaria::class);
    }

    /**
     * Relación: Una propiedad tiene muchos cultivos
     */
    public function cultivos()
    {
        return $this->hasMany(Cultivo::class, 'propiedad_id');
    }

    /**
     * Obtener hectáreas disponibles
     */
    public function getHectareasDisponiblesAttribute(): float
    {
        $hectareasUsadas = $this->cultivos()->sum('hectareas');

        return max(0, $this->hectareas - $hectareasUsadas);
    }
}
