<?php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::where('causer_id', auth()->id());

        // Filtro por descripción
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Filtro por fecha
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $logs = $query->latest()->paginate(10);

        return view('audit.index', compact('logs'));
    }
    public function exportPdf(Request $request)
    {
        $query = Activity::where('causer_id', auth()->id());

        // Filtro por descripción
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Filtro por fecha
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $logs = $query->latest()->get();
        $user = auth()->user();
          // Pasar los logs a la vista PDF
        $pdf = Pdf::loadView('pdf.audits.index', compact('logs','user'));

        // Descargar el PDF
        return $pdf->stream('mi-auditoria.pdf');
    }
}
