<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('staff')->user();

        /*
        |--------------------------------------------------------------------------
        | Todo lo relacionado a productores, hectáreas y gráficos
        | queda comentado porque el dashboard ahora es básico
        |--------------------------------------------------------------------------
        */

        // Total productores
        // $totalProductores = Productor::count();

        // Total hectáreas cultivadas
        // $totalHectareas = (float) Cultivo::sum('hectareas');

        // Timeline últimos 6 meses
        // $from = now()->startOfMonth()->subMonths(5);
        // $to   = now()->endOfMonth();

        // $timelineRaw = Cultivo::query()
        //     ->whereBetween('created_at', [$from, $to])
        //     ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periodo, SUM(hectareas) as total")
        //     ->groupBy('periodo')
        //     ->orderBy('periodo')
        //     ->get();

        // $hectareasTimeline = [];

        // for ($i = 5; $i >= 0; $i--) {
        //     $periodo = now()->startOfMonth()->subMonths($i)->format('Y-m');
        //     $match = $timelineRaw->firstWhere('periodo', $periodo);

        //     $hectareasTimeline[] = [
        //         'fecha' => $periodo,
        //         'total' => $match ? (float) $match->total : 0.0,
        //     ];
        // }

        // Cultivos por tipo
        // $cultivosPorTipo = Cultivo::query()
        //     ->selectRaw('tipo, SUM(hectareas) as total')
        //     ->groupBy('tipo')
        //     ->orderByRaw('SUM(hectareas) DESC')
        //     ->get();

        return Inertia::render('Staff/Dashboard', [
            'user' => $user,
            
        ]);
    }
}
