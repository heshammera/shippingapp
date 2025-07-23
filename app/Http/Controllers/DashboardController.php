<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShippingCompany;
use App\Models\ShipmentStatus;
use App\Models\DeliveryAgent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
public function __construct()
{
    $this->middleware('auth');

    // السماح فقط للأدمن بالدخول للوحة التحكم
    $this->middleware(function ($request, $next) {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('redirect.by.role');
        }
        return $next($request);
    });
}


    /**
     * عرض لوحة التحكم مع الإحصائيات
     */
    public function index()
    {
        // إجمالي عدد الشحنات
        $totalShipments = Shipment::count();
        
        // عدد الشحنات حسب الحالة
        $shipmentsByStatus = Shipment::select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->with('status')
            ->get();
        
        // عدد الشحنات عند كل شركة
        $shipmentsByCompany = ShippingCompany::withCount('shipments')
            ->orderBy('shipments_count', 'desc')
            ->get();
        
        // إجمالي المبالغ المستحقة من كل شركة
        $amountsByCompany = ShippingCompany::select('shipping_companies.id', 'shipping_companies.name')
            ->selectRaw('SUM(shipments.selling_price) as total_amount')
            ->selectRaw('COUNT(shipments.id) as shipments_count')
            ->leftJoin('shipments', 'shipping_companies.id', '=', 'shipments.shipping_company_id')
            ->where('shipments.status_id', 1) // تم التسليم
            ->groupBy('shipping_companies.id', 'shipping_companies.name')
            ->get();
        
        // الربح الكلي (فرق سعر البيع وسعر التكلفة)
$totalProfit = Shipment::whereHas('status', fn($q) => $q->where('name', 'تم التوصيل'))
    ->with('products')
    ->get()
    ->flatMap->products
    ->sum(function ($product) {
        $quantity = $product->pivot->quantity ?? 1;
        $price = $product->pivot->price ?? 0;
        $cost = $product->cost_price ?? 0;
        return ($price - $cost) * $quantity;
    });


        
        // الشحنات المتأخرة لأكثر من 10 أيام
        $delayedShipments = Shipment::where('status_id', 3) // عُهدة
            ->whereDate('shipping_date', '<=', now()->subDays(10))
            ->with(['shippingCompany', 'deliveryAgent'])
            ->get();
        
        // إحصائيات الشهر الحالي
        $currentMonthProfit = Shipment::whereHas('status', fn($q) => $q->where('name', 'تم التوصيل'))
    ->whereYear('delivery_date', now()->year)
    ->whereMonth('delivery_date', now()->month)
    ->with('products')
    ->get()
    ->flatMap->products
    ->sum(function ($product) {
        $quantity = $product->pivot->quantity ?? 1;
        $price = $product->pivot->price ?? 0;
        $cost = $product->cost_price ?? 0;
        return ($price - $cost) * $quantity;
    });

        $currentMonthShipments = Shipment::whereYear('created_at', now()->year)
    ->whereMonth('created_at', now()->month)
    ->count();

        $currentMonthDelivered = Shipment::where('status_id', 1) // تم التسليم
            ->whereYear('delivery_date', now()->year)
            ->whereMonth('delivery_date', now()->month)
            ->count();
        
        $currentMonthReturned = Shipment::where('status_id', 2) // مرتجع
            ->whereYear('return_date', now()->year)
            ->whereMonth('return_date', now()->month)
            ->count();
    

        
// إحصائيات آخر 6 أشهر للرسم البياني
$last6Months = collect([]);

for ($i = 5; $i >= 0; $i--) {
    $month = now()->subMonths($i);
    $monthName = $month->locale('ar')->translatedFormat('F');

    $delivered = Shipment::whereHas('status', fn($q) => $q->where('name', 'تم التوصيل'))
        ->whereYear('delivery_date', $month->year)
        ->whereMonth('delivery_date', $month->month)
        ->count();

    $returned = Shipment::whereHas('status', fn($q) => $q->where('name', 'مرتجع'))
        ->whereYear('return_date', $month->year)
        ->whereMonth('return_date', $month->month)
        ->count();

    $monthShipments = Shipment::whereHas('status', fn($q) => $q->where('name', 'تم التوصيل'))
        ->whereYear('delivery_date', $month->year)
        ->whereMonth('delivery_date', $month->month)
        ->with('products')
        ->get();

    $monthlyProfit = $monthShipments->flatMap->products->sum(function ($product) {
        $quantity = $product->pivot->quantity ?? 1;
        $price = $product->pivot->price ?? 0;
        $cost = $product->cost_price ?? 0;
        return ($price - $cost) * $quantity;
    });

    $last6Months->push([
        'month' => $monthName,
        'delivered' => $delivered,
        'returned' => $returned,
        'profit' => $monthlyProfit
    ]);
}


        
        return view('dashboard.index', compact(
            'totalShipments',
            'shipmentsByStatus',
            'shipmentsByCompany',
            'amountsByCompany',
            'totalProfit',
            'delayedShipments',
            'currentMonthShipments',
            'currentMonthDelivered',
            'currentMonthReturned',
            'currentMonthProfit',
            'last6Months'
        ));
    }
}
