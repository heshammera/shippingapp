@extends('layouts.app')

@section('title', 'تقرير الخزنة')

@section('actions')
    <a href="{{ route('accounting.index') }}" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للوحة الحسابات
    </a>
    <button class="btn btn-sm btn-success" onclick="window.print()">
        <i class="bi bi-printer"></i> طباعة التقرير
    </button>
    <a href="#" class="btn btn-sm btn-primary" id="exportPdf">
        <i class="bi bi-file-pdf"></i> تصدير PDF
    </a>
    <a href="#" class="btn btn-sm btn-success" id="exportExcel">
        <i class="bi bi-file-excel"></i> تصدير Excel
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">تصفية التقرير</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('accounting.treasury-report') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="date_from" class="form-label">من تاريخ</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label for="date_to" class="form-label">إلى تاريخ</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">تصفية</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إجمالي التحصيلات</h5>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalCollections, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">إجمالي المصاريف</h5>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalExpenses, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card {{ $balance >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">رصيد الخزنة</h5>
                    </div>
                    <h2 class="mb-0">{{ number_format($balance, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">سجل الخزنة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>البيان</th>
                        <th>الإيرادات</th>
                        <th>المصروفات</th>
                        <th>الرصيد</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction['date'] }}</td>
                            <td>{{ $transaction['description'] }}</td>
                            <td>
                                @if($transaction['type'] == 'collection')
                                    {{ number_format($transaction['amount'], 2) }} جنيه
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($transaction['type'] == 'expense')
                                    {{ number_format($transaction['amount'], 2) }} جنيه
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ number_format($transaction['running_balance'], 2) }} جنيه</td>
                            <td>{{ $transaction['notes'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد معاملات</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th colspan="2">الإجمالي</th>
                        <th>{{ number_format($totalCollections, 2) }} جنيه</th>
                        <th>{{ number_format($totalExpenses, 2) }} جنيه</th>
                        <th>{{ number_format($balance, 2) }} جنيه</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
