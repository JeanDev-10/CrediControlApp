<?php
namespace App\Http\Controllers;

use App\Services\AuditService;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function __construct(protected AuditService $auditService, protected UserService $userService) {}
    public function index(Request $request)
    {
        $filters = $request->only(['description', 'from', 'to']);
        $logs=$this->auditService->getAll($filters);

        return view('audit.index', compact('logs'));
    }
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['description', 'from', 'to']);
        $logs=$this->auditService->getAllWithoutPaginations($filters);
        $user = $this->userService->getUserLoggedIn();
          // Pasar los logs a la vista PDF
        $pdf = Pdf::loadView('pdf.audits.index', compact('logs','user'));

        // Descargar el PDF
        return $pdf->stream('mi-auditoria.pdf');
    }
}
