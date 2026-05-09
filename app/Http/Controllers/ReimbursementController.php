<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReimbursementController extends Controller
{
    public function index()
    {
        $reimbursements = Auth::user()->reimbursements()
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'reimbursements' => $reimbursements
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'required|in:pending,approved,received',
        ]);

        $reimbursement = Auth::user()->reimbursements()->create($validated);

        return response()->json([
            'success' => true,
            'reimbursement' => $reimbursement
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,received',
        ]);

        $reimbursement = Auth::user()->reimbursements()->findOrFail($id);
        $reimbursement->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $reimbursement = Auth::user()->reimbursements()->findOrFail($id);
        $reimbursement->delete();

        return response()->json(['success' => true]);
    }
}
