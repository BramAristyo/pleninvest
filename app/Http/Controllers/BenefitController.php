<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BenefitController extends Controller
{
    public function index()
    {
        $benefits = Auth::user()->benefits()->get();

        return response()->json([
            'benefits' => $benefits
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $benefit = Auth::user()->benefits()->create($validated);

        return response()->json([
            'success' => true,
            'benefit' => $benefit
        ]);
    }

    public function destroy($id)
    {
        $benefit = Auth::user()->benefits()->findOrFail($id);
        $benefit->delete();

        return response()->json(['success' => true]);
    }
}
