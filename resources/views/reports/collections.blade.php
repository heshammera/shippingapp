@extends('layouts.app')

@section('title', 'تقرير التحصيلات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>📥 تقرير التحصيلات</h4>
    <div>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">🔙 العودة للتقارير</a>
        <a href="#" onclick="window.print()" class="btn btn-success">🖨 طباعة التقرير</a>
        <a href="{{ route('reports.collections.excel', request()->query()) }}" class="btn btn-primary" target="_blank">📥 تصدير Excel</a>
        <a href="{{ route('reports.collections.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">📄 تصدير PDF</a>
    </div>
</div>

<form method="GET" action="{{ route('collections.report') }}" class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="shipping_company_id" class="form-label">شركة الشحن</label>
        <select name="shipping_company_id" id="shipping_company_id" class="form-select">
            <option value="">الكل</option>
            @foreach($shippingCompanies as $company)
                <option value="{{ $company->id }}" @selected(request('shipping_company_id') == $company->id)>
                    {{ $company->name }}
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
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary w-100">🔍 تصفية</button>
    </div>
</form>

<div class="row text-center mb-4">
    <div class="col-md-4">
        <div class="bg-success text-white rounded py-3 shadow-sm">
            <h5>إجمالي التحصيل</h5>
            <h3>{{ number_format($total_collection, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-info text-white rounded py-3 shadow-sm">
            <h5>عدد العمليات</h5>
            <h3>{{ $collections->count() }}</h3>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        قائمة التحصيلات
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>المبلغ</th>
                    <th>شركة الشحن</th>
                    <th>الملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collections as $collect)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $collect->date ?? 'غير محدد' }}</td>
                        <td>{{ number_format($collect->amount, 2) }}</td>
                        <td>{{ $collect->shippingCompany->name ?? 'غير معروف' }}</td>
                        <td>{{ $collect->notes }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">لا توجد تحصيلات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
