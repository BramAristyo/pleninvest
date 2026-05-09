<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Auth::user()->insurances()->get();

        return response()->json([
            'insurances' => $insurances
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'premium' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'coverage' => 'nullable|string',
        ]);

        $insurance = Auth::user()->insurances()->create($validated);

        return response()->json([
            'success' => true,
            'insurance' => $insurance
        ]);
    }

    public function destroy($id)
    {
        $insurance = Auth::user()->insurances()->findOrFail($id);
        $insurance->delete();

        return response()->json(['success' => true]);
    }
}
