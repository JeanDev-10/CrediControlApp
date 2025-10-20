<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function __construct(protected AuditService $auditService, protected UserService $userService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['description', 'from', 'to', 'user_id']);
        $logs = $this->auditService->getAll($filters);
        $users = $this->userService->exportAll([]);

        return view('audit.index', compact('logs', 'users', 'filters'));
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['description', 'from', 'to', 'user_id']);
        // Llama al servicio o repositorio que definiste
        $result = $this->auditService->getAllWithoutPaginations($filters);

        // Destructura el arreglo para obtener variables separadas
        $logs = $result['logs'];
        $user = $result['user'];
        // Pasar los logs a la vista PDF
        $pdf = Pdf::loadView('pdf.audits.index', compact('logs', 'user', 'filters'));

        // Descargar el PDF
        return $pdf->stream('mi-auditoria.pdf');
    }
}
