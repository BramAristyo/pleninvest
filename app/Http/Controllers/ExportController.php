<?php

namespace App\Http\Controllers;

use App\Exports\FinancialExport;
use App\Exports\FinancialSheetExport;
use App\Models\Allowance;
use App\Models\Insurance;
use App\Models\Investment;
use App\Models\Reimbursement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function downloadExcel(Request $request)
    {
        $userId = auth()->id() ?? 1; // Fallback to 1 for demo purposes
        $startMonth = $request->query('start_month');
        $endMonth = $request->query('end_month');

        if (!$startMonth) {
            $startMonth = Carbon::now()->format('Y-m');
        }
        if (!$endMonth) {
            $endMonth = Carbon::now()->format('Y-m');
        }

        $startDate = Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth();

        // 1. Transaksi
        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get(['date', 'type', 'category', 'note', 'amount', 'benefit_type', 'benefit_note']);

        // 2. Reimburse
        $reimbursements = Reimbursement::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get(['date', 'category', 'note', 'amount', 'status']);

        // 3. Diversifikasi
        $investments = Investment::where('user_id', $userId)
            ->get(['name', 'type', 'purchase_date', 'buy_price', 'quantity', 'ticker', 'note', 'current_price']);

        // 4. Asuransi
        $insurances = Insurance::where('user_id', $userId)
            ->get(['name', 'type', 'premium', 'due_date', 'coverage']);

        // 5. Tunjangan
        $allowances = Allowance::where('user_id', $userId)
            ->get(['name', 'type', 'amount', 'note']);

        $sheets = [
            new FinancialSheetExport($transactions, 'Transaksi', ['Tanggal', 'Jenis', 'Kategori', 'Keterangan', 'Nominal', 'Tipe Benefit', 'Catatan Benefit']),
            new FinancialSheetExport($reimbursements, 'Reimburse', ['Tanggal', 'Kategori', 'Keterangan', 'Nominal', 'Status']),
            new FinancialSheetExport($investments, 'Diversifikasi', ['Nama', 'Jenis', 'Tgl Beli', 'Harga Beli', 'Jumlah', 'Ticker', 'Catatan', 'Harga Kini']),
            new FinancialSheetExport($insurances, 'Asuransi', ['Nama', 'Jenis', 'Premi', 'Jatuh Tempo', 'Cakupan']),
            new FinancialSheetExport($allowances, 'Tunjangan', ['Nama', 'Jenis', 'Nominal', 'Catatan']),
        ];

        return Excel::download(new FinancialExport($sheets), "Financial_Export_{$startMonth}_to_{$endMonth}.xlsx");
    }
}
