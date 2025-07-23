@extends('layouts.app')

@section('title', 'تقرير الشحنات (PDF)')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>تقرير الشحنات</h1>
        <p>
            @if(isset($filters['date_from']) && $filters['date_from'])
                من تاريخ: {{ $filters['date_from'] }}
            @endif
            
            @if(isset($filters['date_to']) && $filters['date_to'])
                إلى تاريخ: {{ $filters['date_to'] }}
            @endif
        </p>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
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
            <div class="card bg-light">
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
            <div class="card bg-light">
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
            <div class="card bg-light">
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

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
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
                    @endphp
                    <tr>
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
            <tfoot>
                <tr>
                    <th colspan="4">الإجمالي</th>
                    <th>{{ number_format($totalCost, 2) }}</th>
                    <th>{{ number_format($totalSelling, 2) }}</th>
                    <th>{{ number_format($totalProfit, 2) }}</th>
                    <th colspan="4"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
