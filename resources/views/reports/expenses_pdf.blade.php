@extends('layouts.app')

@section('title', 'تقرير المصاريف (PDF)')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>تقرير المصاريف</h1>
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
                            <h5 class="mb-0">إجمالي المصاريف</h5>
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
                    <th>رقم المصروف</th>
                    <th>العنوان</th>
                    <th>المبلغ</th>
                    <th>تاريخ المصروف</th>
                    <th>ملاحظات</th>
                    <th>تم بواسطة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->title }}</td>
                        <td>{{ number_format($expense->amount, 2) }} جنيه</td>
                        <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td>{{ $expense->notes ?? '-' }}</td>
                        <td>{{ $expense->createdBy->name ?? 'غير محدد' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد مصاريف</td>
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

    <h4>المصاريف حسب الشهر</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>الشهر</th>
                    <th>عدد المصاريف</th>
                    <th>إجمالي المبلغ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expensesByMonth as $month)
                    <tr>
                        <td>{{ $month['month_name'] }}</td>
                        <td>{{ $month['count'] }}</td>
                        <td>{{ number_format($month['total'], 2) }} جنيه</td>
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
                    <th>{{ $expenses->count() }}</th>
                    <th>{{ number_format($total, 2) }} جنيه</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
