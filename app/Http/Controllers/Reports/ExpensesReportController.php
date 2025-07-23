<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpensesReportController extends Controller
{
   public function index(Request $request)
{
    $query = \App\Models\Expense::with('user');

    if ($request->filled('date_from')) {
        $query->whereDate('date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('date', '<=', $request->date_to);
    }

    $expenses = $query->get();
    $total_expenses = $expenses->sum('amount');

    return view('reports.expenses', compact('expenses', 'total_expenses'));
}

}
