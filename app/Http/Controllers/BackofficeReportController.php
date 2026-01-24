<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BackofficeReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end   = $request->query('end', now()->toDateString());

        $startDate = Carbon::parse($start)->startOfDay();
        $endDate   = Carbon::parse($end)->endOfDay();

        $query = Order::query()->whereBetween('created_at', [$startDate, $endDate]);

        $totalPenjualan  = (float) ($query->sum('total') ?? 0);
        $jumlahTransaksi = (int) ($query->count() ?? 0);
        return view('backoffice.reports', [
    'start' => $start,
    'end' => $end,
    'totalPenjualan' => (float) ($totalPenjualan ?? 0),
    'jumlahTransaksi' => (int) ($jumlahTransaksi ?? 0),
]);

      }

    // PDF 
    public function print(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end   = $request->query('end', now()->toDateString());

        [$totalPenjualan, $jumlahTransaksi] = $this->summary($start, $end);

        return view('backoffice.reports_print', compact(
            'start', 'end', 'totalPenjualan', 'jumlahTransaksi'
        ));
    }

    // pengganti Excel export CSV (dibuka Excel juga bisa)
    public function exportCsv(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
    $end   = $request->query('end', now()->toDateString());

    [$totalPenjualan, $jumlahTransaksi] = $this->summary($start, $end);

    $filename = "laporan-{$start}-{$end}.csv";

    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    // ✅ HEADER DI ATAS, DATA DI BAWAH (1 baris)
    $rows = [
        ['Start', 'End', 'Total Penjualan', 'Jumlah Transaksi'],
        [$start, $end, $totalPenjualan, $jumlahTransaksi],
    ];

    return response()->stream(function () use ($rows) {
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
        foreach ($rows as $r) fputcsv($out, $r);
        fclose($out);
    }, 200, $headers);
}
private function summary(string $start, string $end): array
{
    $startDate = Carbon::parse($start)->startOfDay();
    $endDate   = Carbon::parse($end)->endOfDay();

    $query = Order::query()->whereBetween('created_at', [$startDate, $endDate]);

    return [
        (float) $query->sum('total'),
        (int) $query->count(),
    ];
}
}