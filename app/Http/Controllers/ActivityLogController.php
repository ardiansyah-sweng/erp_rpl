<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Constants\ActivityLogColumns;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan halaman list log aktivitas.
     * Mendukung search, filter modul, filter aksi, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $module = $request->input('module');
        $action = $request->input('action');

        $logs = ActivityLog::getAll($search, $module, $action);

        // Data untuk dropdown filter
        $modules = ActivityLogColumns::getModules();
        $actions = ActivityLogColumns::getActions();

        return view('activity_log.index', compact('logs', 'modules', 'actions', 'search', 'module', 'action'));
    }

    /**
     * Cetak laporan PDF log aktivitas.
     * Mendukung filter yang sama dengan halaman index.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $module = $request->input('module');
        $action = $request->input('action');

        // Ambil semua data tanpa pagination untuk PDF
        $query = ActivityLog::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where(ActivityLogColumns::DESCRIPTION, 'LIKE', "%{$search}%")
                  ->orWhere(ActivityLogColumns::USER_NAME, 'LIKE', "%{$search}%")
                  ->orWhere(ActivityLogColumns::TARGET_ID, 'LIKE', "%{$search}%");
            });
        }

        if ($module) {
            $query->where(ActivityLogColumns::MODULE, $module);
        }

        if ($action) {
            $query->where(ActivityLogColumns::ACTION, $action);
        }

        $logs = $query->orderBy(ActivityLogColumns::CREATED_AT, 'desc')->get();

        $modules = ActivityLogColumns::getModules();
        $actions = ActivityLogColumns::getActions();

        $pdf = Pdf::loadView('activity_log.pdf', compact('logs', 'modules', 'actions', 'search', 'module', 'action'));
        return $pdf->stream('laporan-log-aktivitas.pdf');
    }
}
