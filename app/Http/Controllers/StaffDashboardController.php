<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cultivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StaffDashboardController extends Controller
{
    /**
     * Total de hectáreas del departamento de Lavalle (Mendoza)
     * Valor aproximado: 10,242 hectáreas
     */
    private const HECTAREAS_TOTAL_LAVALLE = 10242;

    public function index(Request $request)
    {
        $user = Auth::guard('staff')->user();

        // KPIs - Usuarios
        $usuariosTotal = User::count();
        $usuariosNuevos30d = User::where('created_at', '>=', now()->subDays(30))->count();

        // Usuarios nuevos por mes (últimos 6 meses)
        $usuariosNuevosPorMes = $this->getUsuariosNuevosPorMes(6);

        // KPIs - Hectáreas
        $hectareasCultivadas = (float) Cultivo::sum('hectareas');
        $hectareasTotalLavalle = self::HECTAREAS_TOTAL_LAVALLE;
        
        // Calcular porcentaje y restantes
        $hectareasRestantes = max(0, $hectareasTotalLavalle - $hectareasCultivadas);
        $porcentajeOcupado = min(100, round(($hectareasCultivadas / $hectareasTotalLavalle) * 100, 1));

        return Inertia::render('Staff/Dashboard', [
            'user' => $user,
            'kpiData' => [
                'usuarios' => [
                    'total' => $usuariosTotal,
                    'nuevos30d' => $usuariosNuevos30d,
                    'nuevosPorMes' => $usuariosNuevosPorMes,
                ],
                'hectareas' => [
                    'cultivadas' => $hectareasCultivadas,
                    'totalLavalle' => $hectareasTotalLavalle,
                    'restantes' => $hectareasRestantes,
                    'porcentaje' => $porcentajeOcupado,
                ],
            ],
        ]);
    }

    /**
     * Obtiene usuarios nuevos por mes para los últimos N meses
     *
     * @param int $meses Número de meses hacia atrás (default: 6)
     * @return array ['labels' => [...], 'data' => [...]]
     */
    private function getUsuariosNuevosPorMes(int $meses = 6): array
    {
        $from = now()->startOfMonth()->subMonths($meses - 1);
        $to = now()->endOfMonth();

        // Consulta agrupada por mes
        $registrosPorMes = User::query()
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periodo, COUNT(*) as total")
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->pluck('total', 'periodo')
            ->toArray();

        // Construir arrays para Chart.js
        $labels = [];
        $data = [];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $periodo = $fecha->format('Y-m');
            $nombreMes = $fecha->translatedFormat('M Y'); // Ej: "ene 2025"

            $labels[] = $nombreMes;
            $data[] = $registrosPorMes[$periodo] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
