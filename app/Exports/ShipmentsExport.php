<?php

namespace App\Exports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ShipmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $shipments;
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Shipment::with(['shippingCompany', 'deliveryAgent', 'status']);
        
        // تطبيق الفلاتر
        if (isset($this->filters['shipping_company_id']) && $this->filters['shipping_company_id']) {
            $query->where('shipping_company_id', $this->filters['shipping_company_id']);
        }
        
        if (isset($this->filters['status_id']) && $this->filters['status_id']) {
            $query->where('status_id', $this->filters['status_id']);
        }
        
        if (isset($this->filters['delivery_agent_id']) && $this->filters['delivery_agent_id']) {
            $query->where('delivery_agent_id', $this->filters['delivery_agent_id']);
        }
        
        if (isset($this->filters['date_from']) && $this->filters['date_from']) {
            $query->where('shipping_date', '>=', $this->filters['date_from']);
        }
        
        if (isset($this->filters['date_to']) && $this->filters['date_to']) {
            $query->where('shipping_date', '<=', $this->filters['date_to']);
        }
        
        return $query->orderBy('shipping_date', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'رقم التتبع',
            'اسم العميل',
            'رقم الهاتف',
            'العنوان',
            'المنتج',
            'الكمية',
            'سعر التكلفة',
            'سعر البيع',
            'الربح',
            'شركة الشحن',
            'المندوب',
            'الحالة',
            'تاريخ الشحن',
            'تاريخ التسليم',
            'ملاحظات'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($shipment): array
    {
        $profit = $shipment->selling_price - $shipment->cost_price;
        
        return [
            $shipment->tracking_number,
            $shipment->customer_name,
            $shipment->customer_phone,
            $shipment->customer_address,
            $shipment->product_name,
            $shipment->quantity,
            $shipment->cost_price,
            $shipment->selling_price,
            $profit,
            $shipment->shippingCompany->name ?? 'غير محدد',
            $shipment->deliveryAgent->name ?? 'غير محدد',
            $shipment->status->name ?? 'غير محدد',
            $shipment->shipping_date ? $shipment->shipping_date->format('Y-m-d') : 'غير محدد',
            $shipment->delivery_date ? $shipment->delivery_date->format('Y-m-d') : 'غير محدد',
            $shipment->notes
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // تنسيق الصف الأول (العناوين)
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E9ECEF']
                ],
            ],
        ];
    }


}
