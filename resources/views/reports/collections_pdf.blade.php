@extends('layouts.app')

@section('title', 'تقرير التحصيلات (PDF)')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>تقرير التحصيلات</h1>
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
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">إجمالي التحصيلات</h5>
                        </div>
                        <h2 class="mb-0">{{ number_format($total, 2) }} جنيه</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>رقم التحصيل</th>
                    <th>شركة الشحن</th>
                    <th>المبلغ</th>
                    <th>تاريخ التحصيل</th>
                    <th>ملاحظات</th>
                    <th>تم بواسطة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->id }}</td>
                        <td>{{ $collection->shippingCompany->name ?? 'غير محدد' }}</td>
                        <td>{{ number_format($collection->amount, 2) }} جنيه</td>
                        <td>{{ $collection->collection_date->format('Y-m-d') }}</td>
                        <td>{{ $collection->notes ?? '-' }}</td>
                        <td>{{ $collection->createdBy->name ?? 'غير محدد' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد تحصيلات</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">الإجمالي</th>
                    <th>{{ number_format($total, 2) }} جنيه</th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <h4>التحصيلات حسب شركة الشحن</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>شركة الشحن</th>
                    <th>عدد التحصيلات</th>
                    <th>إجمالي المبلغ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collectionsByCompany as $company)
                    <tr>
                        <td>{{ $company['company_name'] }}</td>
                        <td>{{ $company['count'] }}</td>
                        <td>{{ number_format($company['total'], 2) }} جنيه</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>الإجمالي</th>
                    <th>{{ $collections->count() }}</th>
                    <th>{{ number_format($total, 2) }} جنيه</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
