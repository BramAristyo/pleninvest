<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', date('Y-m'));
        
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        return response()->json([
            'transactions' => $transactions,
            'stats' => [
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'benefit_type' => 'nullable|string',
            'benefit_note' => 'nullable|string',
        ]);

        $transaction = Auth::user()->transactions()->create($validated);

        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*.date' => 'required|date',
            'transactions.*.type' => 'required|in:income,expense',
            'transactions.*.category' => 'required|string',
            'transactions.*.amount' => 'required|numeric|min:0',
            'transactions.*.note' => 'nullable|string',
        ]);

        $data = array_map(function($item) {
            return array_merge($item, [
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $request->transactions);

        Transaction::insert($data);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $transaction = Auth::user()->transactions()->findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => true]);
    }
}
