<?php

namespace App\Http\Controllers;

use App\Models\NetWorth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetWorthController extends Controller
{
    public function show()
    {
        $netWorth = NetWorth::where('user_id', Auth::id())->first();

        return response()->json([
            'netWorth' => $netWorth
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'savings' => 'required|numeric|min:0',
            'emergency_fund' => 'required|numeric|min:0',
            'investments' => 'required|numeric|min:0',
            'other_assets' => 'required|numeric|min:0',
            'debt' => 'required|numeric|min:0',
        ]);

        $netWorth = NetWorth::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return response()->json([
            'success' => true,
            'netWorth' => $netWorth
        ]);
    }
}
