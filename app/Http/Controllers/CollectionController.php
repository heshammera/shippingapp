<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    /**
     * عرض قائمة التحصيلات
     */
    public function index(Request $request)
    {
        $query = Collection::with(['shippingCompany', 'createdBy']);
        
        // تصفية حسب شركة الشحن
        if ($request->has('shipping_company_id') && $request->shipping_company_id) {
            $query->where('shipping_company_id', $request->shipping_company_id);
        }
        
        // تصفية حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->where('collection_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('collection_date', '<=', $request->date_to);
        }
        
        $collections = $query->orderBy('collection_date', 'desc')->paginate(15);
        $shippingCompanies = ShippingCompany::all();
        
        return view('collections.index', compact('collections', 'shippingCompanies'));
    }

    /**
     * عرض نموذج إنشاء تحصيل جديد
     */
    public function create()
    {
        $shippingCompanies = ShippingCompany::all();
        return view('collections.create', compact('shippingCompanies'));
    }

    /**
     * حفظ تحصيل جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_company_id' => 'required|exists:shipping_companies,id',
            'amount' => 'required|numeric|min:0',
            'collection_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        Collection::create([
            'shipping_company_id' => $request->shipping_company_id,
            'amount' => $request->amount,
            'collection_date' => $request->collection_date,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('collections.index')
            ->with('success', 'تم إضافة التحصيل بنجاح');
    }

    /**
     * عرض تفاصيل التحصيل
     */
    public function show(Collection $collection)
    {
        return view('collections.show', compact('collection'));
    }

    /**
     * عرض نموذج تعديل التحصيل
     */
    public function edit(Collection $collection)
    {
        $shippingCompanies = ShippingCompany::all();
        return view('collections.edit', compact('collection', 'shippingCompanies'));
    }

    /**
     * تحديث التحصيل
     */
    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'shipping_company_id' => 'required|exists:shipping_companies,id',
            'amount' => 'required|numeric|min:0',
            'collection_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        $collection->update([
            'shipping_company_id' => $request->shipping_company_id,
            'amount' => $request->amount,
            'collection_date' => $request->collection_date,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('collections.index')
            ->with('success', 'تم تحديث التحصيل بنجاح');
    }

    /**
     * حذف التحصيل
     */
    public function destroy(Collection $collection)
    {
        $collection->delete();
        
        return redirect()->route('collections.index')
            ->with('success', 'تم حذف التحصيل بنجاح');
    }
    
    /**
     * عرض تقرير التحصيلات
     */
    public function report(Request $request)
    {
        $query = Collection::with(['shippingCompany']);
        
        // تصفية حسب شركة الشحن
        if ($request->has('shipping_company_id') && $request->shipping_company_id) {
            $query->where('shipping_company_id', $request->shipping_company_id);
        }
        
        // تصفية حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->where('collection_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('collection_date', '<=', $request->date_to);
        }
        
        $collections = $query->orderBy('collection_date', 'desc')->get();
        $total = $collections->sum('amount');
        
        $shippingCompanies = ShippingCompany::all();
        
        // تجميع البيانات حسب شركة الشحن
        $collectionsByCompany = $collections->groupBy('shipping_company_id')
            ->map(function ($items, $key) {
                $companyName = $items->first()->shippingCompany->name;
                $total = $items->sum('amount');
                return [
                    'company_name' => $companyName,
                    'total' => $total,
                    'count' => $items->count(),
                ];
            });
        
        return view('collections.report', compact('collections', 'shippingCompanies', 'total', 'collectionsByCompany'));
    }
}
