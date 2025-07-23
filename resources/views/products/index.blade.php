@extends('layouts.app')
<style>
    td, th {
        vertical-align: middle !important;
        text-align: center;
    }

    .badge {
        font-size: 1rem !important;
        padding: 6px 12px !important;
        border-radius: 8px;
        font-weight: bold;
    }

 .color-size-badge {
    background-color: #e0f0ff; /* لون أزرق فاتح */
    color: #000;
    border: 1px solid #aad4f5;
    font-size: 0.85rem;
    padding: 5px 10px;
    border-radius: 6px;
    margin: 3px 2px;
    display: inline-block;
    font-weight: 500;
}


    .table-dark th {
        background-color: #343a40 !important;
        color: #fff !important;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    /* تخفيف لون زر الحذف */
    .btn-danger {
        background-color: #e74c3c !important;
        border: none;
    }

    /* تظليل الصفوف لتكون أوضح */
    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    table.table-striped tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    /* توسيع الأعمدة شوية */
    table th, table td {
        padding: 12px 8px;
    }
</style>






@section('title', 'المنتجات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📦 قائمة المنتجات</h5>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">➕ إضافة منتج جديد</a>
    </div>

    <div class="card-body">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center table-sm">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>السعر</th>
                        <th>التكلفة</th>
                        <th>الربح لكل قطعة</th>
                        <th>الألوان</th>
                        <th>المقاسات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td class="text-wrap">{{ $product->name }}</td>

<td><span class="badge bg-success fs-6 px-3 py-2">{{ number_format($product->price) }} ج.م</span></td>
<td><span class="badge bg-success fs-6 px-3 py-2">{{ number_format($product->cost_price, 2) }} ج.م</span></td>
<td><span class="badge bg-success fs-6 px-3 py-2">{{ number_format($product->price - $product->cost_price, 2) }} ج.م</span></td>
<td>

    @php
        $colorClasses = [
            'أبيض' => '#f8f9fa',
            'أسود' => '#000000',
            'بيبي بلو' => '#82dcff',
            'بيج' => '#f5f5dc',
            'بيبي بينك' => '#f3a2b6',
            'رصاصي' => '#6c757d',
            'بني' => '#3b2307',
            'فوشيا' => '#b1088c',
            'برتقالي' => '#fd7e14',
        ];
    @endphp

    @foreach(explode(',', $product->colors) as $color)
        @php
            $bg = $colorClasses[trim($color)] ?? '#e0e0e0';
            $textColor = in_array($bg, ['#f5f5dc', '#f8f9fa', '#e0e0e0']) ? '#000' : '#fff';
        @endphp
        <span class="badge" style="
            background-color: {{ $bg }};
            color: {{ $textColor }};
            border: 1px solid #ccc;
            font-size: 0.9rem;
            padding: 6px 10px;
            margin: 2px;
            border-radius: 8px;
            display: inline-block;
        ">
            {{ trim($color) }}
        </span>
    @endforeach
</td>





                        <td>
    @foreach(explode(',', $product->sizes) as $size)
        <span class="color-size-badge">{{ trim($size) }}</span>
    @endforeach
</td>


 <td>
    <div class="d-flex justify-content-center flex-wrap gap-2">
        <a href="/products/{{ $product->id }}/edit" class="btn btn-warning btn-sm">تعديل</a>
        <a href="{{ route('product.prices.edit', $product->id) }}" class="btn btn-primary btn-sm">الأسعار</a>
        <form action="/products/{{ $product->id }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
        </form>
    </div>
</td>




                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info text-center">لا توجد منتجات مضافة حتى الآن.</div>
        @endif
    </div>
</div>
@endsection
