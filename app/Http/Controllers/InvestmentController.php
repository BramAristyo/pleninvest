<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Auth::user()->investments()->get();

        $totalModal = 0;
        $totalCurrent = 0;

        foreach ($investments as $inv) {
            $totalModal += $inv->buy_price * $inv->quantity;
            $totalCurrent += $inv->current_price * $inv->quantity;
        }

        $pl = $totalCurrent - $totalModal;
        $ret = $totalModal > 0 ? ($pl / $totalModal * 100) : 0;

        return response()->json([
            'investments' => $investments,
            'stats' => [
                'total_modal' => $totalModal,
                'total_current' => $totalCurrent,
                'pl' => $pl,
                'return_pct' => round($ret, 2)
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'purchase_date' => 'nullable|date',
            'buy_price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'ticker' => 'nullable|string',
            'note' => 'nullable|string',
            'current_price' => 'nullable|numeric|min:0',
        ]);

        if (!isset($validated['current_price'])) {
            $validated['current_price'] = $validated['buy_price'];
        }

        $investment = Auth::user()->investments()->create($validated);

        return response()->json([
            'success' => true,
            'investment' => $investment
        ]);
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'current_price' => 'required|numeric|min:0',
        ]);

        $investment = Auth::user()->investments()->findOrFail($id);
        $investment->update([
            'current_price' => $request->current_price
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $investment = Auth::user()->investments()->findOrFail($id);
        $investment->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
