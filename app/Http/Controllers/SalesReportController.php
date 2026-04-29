<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index()
    {
        return view('sales_report.summary', $this->getSummaryData());
    }

    public function exportPdf()
    {
        $data = array_merge($this->getSummaryData(), [
            'generatedAt' => Carbon::now()->format('d-m-Y H:i:s'),
        ]);

        $pdf = Pdf::loadView('sales_report.pdf', $data);

        return $pdf->stream('sales-report-summary.pdf');
    }

    private function getSummaryData(): array
    {
        $summary = PurchaseOrder::query()
            ->selectRaw('COALESCE(SUM(total), 0) as total_sales')
            ->selectRaw('COUNT(*) as transaction_count')
            ->selectRaw('COALESCE(AVG(total), 0) as average_transaction_value')
            ->first();

        return [
            'totalSales' => (float) ($summary->total_sales ?? 0),
            'transactionCount' => (int) ($summary->transaction_count ?? 0),
            'averageTransactionValue' => (float) ($summary->average_transaction_value ?? 0),
        ];
    }
}
