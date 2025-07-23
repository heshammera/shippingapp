  <style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.page-item {
    margin: 0 3px;
}

.page-link {
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.5;
    border-radius: 6px;
}

.page-link svg, 
.page-link span {
    width: 14px;
    height: 14px;
}

.page-link:hover {
    background-color: #f0f0f0;
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@stack('scripts')
@if($shipments->count() > 0)
  <div class="table-responsive">


<table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th> <!-- ✅ زرار تحديد الكل -->
                        <th>رقم التتبع</th>
                        <th>العميل</th>
                        <th>رقم الهاتف</th>
                        <th>المحافظة </th>
                        <th>العنوان </th>

                        <th>PRD</th>
                        <th>TOT</th>

                        <th>شركة الشحن</th>
                        <th> المندوب</th>

                        <th>الحالة</th>
                        <th>تاريخ الشحن</th>
                        <th>تاريخ الإرجاع</th>

                        <th>🖨</th>

                        @if(auth()->user()->role !== 'delivery_agent')
        <th>الإجراءات</th>
                        @endif

                    </tr>
                </thead>
                <tbody>
                    @foreach($shipments as $shipment)
                    
<tr id="shipment-row-{{ $shipment->id }}" class="{{ $shipment->status->color ?? 'table-default' }}">
<td>
      
        <input type="checkbox" class="select-shipment" value="{{ $shipment->id }}">
    </td>
<td style="text-align: center; vertical-align: middle; padding: 0;">
    <div style="display: inline-block; margin: 0; padding: 0; line-height: 1;">
        {!! DNS1D::getBarcodeHTML($shipment->tracking_number, 'C128', .6, 40) !!}
    </div>
        <div class="text-center small">{{ $shipment->tracking_number }}</div>

</td>                        <td>{{ $shipment->customer_name }}</td>
                        <td>{{ $shipment->customer_phone }}</td>
                        <td>{{ $shipment->governorate }}</td>
                        <td>{{ $shipment->customer_address }}</td>
<td>
    @foreach($shipment->products as $product)
        <div class="mb-1"  style="text-align: center;">
            <strong>{{ $product->pivot->color }}</strong><br>
            <span class="badge bg-dark">
                {{ $product->pivot->quantity }} × {{ number_format($product->pivot->price, 2) }} ج.م
            </span>
        </div>
    @endforeach
</td>



                        <td>{{ is_numeric($shipment->total_amount) ? number_format($shipment->total_amount, 0) : '—' }}</td>

 

                        <td>
                             @if(auth()->user()->role === 'delivery_agent')
        {{ $shipment->shippingCompany->name ?? '-' }}
    @else
<select class="form-select form-select update-shipping-company form-select-sm"
        @if($shipment?->id)
    data-url="/shipments/{{ $shipment->id }}/quick-update"
@endif

        data-id="{{ $shipment->id }}">
        @foreach($shippingCompanies as $company)
            <option value="{{ $company->id }}" 
                {{ $shipment->shipping_company_id == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach
         @endif
    </select>
                        </td>
                        
                        
<td>
    <select class="form-select assign-agent form-select-sm"
            data-id="{{ $shipment->id }}"
@if($shipment?->id)
    data-url="{{ route('shipments.assignAgent', $shipment->id) }}"
@endif
            {{ $shipment->shipping_company_id == 7 ? '' : 'disabled' }}>
        <option value="" {{ is_null($shipment->delivery_agent_id) ? 'selected' : '' }}>غير محدد</option>
        @foreach($deliveryAgents as $agent)
            <option value="{{ $agent->id }}" {{ $shipment->delivery_agent_id == $agent->id ? 'selected' : '' }}>
                {{ $agent->name }}
            </option>
        @endforeach
    </select>
</td>


                        
                        <td>
<select class="form-select update-status form-select-sm" 
        data-url="/shipments/{{ $shipment->id }}/quick-update">
    @foreach($statuses as $status)
        <option value="{{ $status->id }}" {{ $shipment->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
    @endforeach
</select>

                            
<span class="badge shipment-status-badge {{ $shipment->status->color ?? 'bg-secondary' }}">
    {{ $shipment->status->name ?? 'غير محدد' }}
</span>




                        </td>
                        
<td>
    @if(auth()->user()->role !== 'delivery_agent')
        <input type="date"
            class="form-control form-control-sm"
            name="shipping_date"
            data-url="/shipments/{{ $shipment->id }}/quick-update"
            value="{{ $shipment->shipping_date ? \Carbon\Carbon::parse($shipment->shipping_date)->format('Y-m-d') : '' }}">
    @else
        {{ $shipment->shipping_date ? \Carbon\Carbon::parse($shipment->shipping_date)->format('Y-m-d') : '-' }}
    @endif
</td>

<td>
    @if(auth()->user()->role !== 'delivery_agent')
        <input type="date"
            class="form-control return-date-input form-control-sm"
            data-id="{{ $shipment->id }}"
            value="{{ $shipment->return_date ? \Carbon\Carbon::parse($shipment->return_date)->format('Y-m-d') : '' }}">
    @else
        {{ $shipment->return_date ? \Carbon\Carbon::parse($shipment->return_date)->format('Y-m-d') : '-' }}
    @endif
</td>


                        <td>
    @if($shipment->is_printed)
        ✅
    @else
        ❌
    @endif
</td>



<td style="position: relative; padding-top: 18px;">

    {{-- تاريخ الطباعة بخط صغير في الأعلى على اليسار --}}
    <div style="font-size: 10px; color: gray; position: absolute; top: 4px; left: 4px;">
        {{ $shipment->print_date ? \Carbon\Carbon::parse($shipment->print_date)->format('Y-m-d') : '-' }}
    </div>

    <div class="btn-group" role="group">
        @if(auth()->user()->role === 'delivery_agent')
            <a href="/shipments/{{ $shipment->id }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        @else
            <a href="/shipments/{{ $shipment->id }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
            <a href="/shipments/{{ $shipment->id }}/edit" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
            </a>            
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $shipment->id }}"><i class="bi bi-trash"></i></button>
        @endif
    </div>


    
    @if(auth()->user()->role !== 'delivery_agent')
    <!-- Modal الحذف -->
    <div class="modal fade" id="deleteModal{{ $shipment->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $shipment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف الشحنة رقم "{{ $shipment->tracking_number }}"؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    @if($shipment->id)
                                            <form action="/shipments/{{ $shipment->id }}/quick-delete" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">حذف</button>
                                            </form>
                                            @else
                                                <span class="text-danger">ID مفقود</span>
                                            @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</td>







                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
                </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $shipments->appends(request()->query())->links() }}
    </div>
@else
    <div class="alert alert-info">
        لا توجد شحنات مطابقة لمعايير البحث.
    </div>
@endif
<script>
    // ✅ تشغيل بعد تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.collection-checkbox');
        const exportBtn = document.getElementById('export-selected');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });
        }

        if (exportBtn) {
            exportBtn.addEventListener('click', function (e) {
                e.preventDefault();

                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    alert('يرجى تحديد صفوف أولاً.');
                    return;
                }

                const url = "{{ route('reports.collections.excel') }}?ids=" + selected.join(',');
                window.open(url, '_blank');
            });
        }
    });
</script>
