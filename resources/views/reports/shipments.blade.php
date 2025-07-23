@extends('layouts.app')

@section('title', 'تقرير الشحنات')

@section('actions')
    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للتقارير
    </a>
    <button class="btn btn-sm btn-success" onclick="window.print()">
        <i class="bi bi-printer"></i> طباعة التقرير
    </button>
    <a href="{{ route('reports.shipments.excel', request()->all()) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-file-excel"></i> تصدير Excel
    </a>
    <a href="{{ route('reports.shipments.pdf', request()->all()) }}" class="btn btn-sm btn-danger">
        <i class="bi bi-file-pdf"></i> تصدير PDF
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">تصفية التقرير</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.shipments') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="shipping_company_id" class="form-label">شركة الشحن</label>
                <select name="shipping_company_id" id="shipping_company_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($shippingCompanies as $company)
                        <option value="{{ $company->id }}" {{ request('shipping_company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status_id" class="form-label">حالة الشحنة</label>
                <select name="status_id" id="status_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($shipmentStatuses as $status)
                        <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="delivery_agent_id" class="form-label">المندوب</label>
                <select name="delivery_agent_id" id="delivery_agent_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($deliveryAgents as $agent)
                        <option value="{{ $agent->id }}" {{ request('delivery_agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from" class="form-label">من تاريخ</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">إلى تاريخ</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">تصفية</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4 mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إجمالي الشحنات</h5>
                    </div>
                    <h2 class="mb-0">{{ $totalShipments }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إجمالي التكلفة</h5>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalCost, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إجمالي البيع</h5>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalSelling, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إجمالي الربح</h5>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalProfit, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">قائمة الشحنات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>رقم التتبع</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>التكلفة</th>
                        <th>البيع</th>
                        <th>الربح</th>
                        <th>شركة الشحن</th>
                        <th>المندوب</th>
                        <th>الحالة</th>
                        <th>تاريخ الشحن</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                        @php
                            $profit = $shipment->selling_price - $shipment->cost_price;
                            $statusClass = '';
                            if ($shipment->status) {
                                if (strpos(strtolower($shipment->status->name), 'delivered') !== false || strpos($shipment->status->name, 'تم التسليم') !== false) {
                                    $statusClass = 'status-delivered';
                                } elseif (strpos(strtolower($shipment->status->name), 'returned') !== false || strpos($shipment->status->name, 'مرتجع') !== false) {
                                    $statusClass = 'status-returned';
                                } elseif (strpos(strtolower($shipment->status->name), 'custody') !== false || strpos($shipment->status->name, 'عهدة') !== false) {
                                    $statusClass = 'status-custody';
                                }
                            }
                        @endphp
                        <tr class="{{ $statusClass }}">
                            <td>{{ $shipment->tracking_number }}</td>
                            <td>{{ $shipment->customer_name }}</td>
                            <td>{{ $shipment->product_name }}</td>
                            <td>{{ $shipment->quantity }}</td>
                            <td>{{ number_format($shipment->cost_price, 2) }}</td>
                            <td>{{ number_format($shipment->selling_price, 2) }}</td>
                            <td>{{ number_format($profit, 2) }}</td>
                            <td>{{ $shipment->shippingCompany->name ?? 'غير محدد' }}</td>
                            <td>{{ $shipment->deliveryAgent->name ?? 'غير محدد' }}</td>
                            <td>{{ $shipment->status->name ?? 'غير محدد' }}</td>
                            <td>{{ $shipment->shipping_date ? $shipment->shipping_date->format('Y-m-d') : 'غير محدد' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">لا توجد شحنات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection
