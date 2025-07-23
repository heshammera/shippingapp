<?php


namespace App\Http\Controllers\Reports;
use App\Exports\CollectionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\ShippingCompany;

class CollectionsReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Collection::with('shippingCompany');

        if ($request->filled('shipping_company_id')) {
            $query->where('shipping_company_id', $request->shipping_company_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('collection_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('collection_date', '<=', $request->date_to);
        }

        $collections = $query->get();
        $total_collection = $collections->sum('amount');
        $shippingCompanies = ShippingCompany::all();

        return view('reports.collections', compact('collections', 'total_collection', 'shippingCompanies'));
    }

    public function exportExcel(Request $request)
{
    $timestamp = now()->format('Y-m-d_H-i-s');
    return Excel::download(new CollectionsExport($request), "تقرير_التحصيلات_{$timestamp}.xlsx");
}


    
}

