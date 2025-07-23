<?php

namespace App\Exports;

use App\Models\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CollectionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

   public function __construct(Request $request)
{
    $this->request = $request;
}

public function collection()
{
    $query = Collection::with('shippingCompany');

    if ($this->request->filled('ids')) {
        $ids = explode(',', $this->request->ids);
        $query->whereIn('id', $ids);
    }

    return $query->get()->map(function ($item) {
        return [
            'التاريخ' => $item->collection_date,
            'المبلغ' => $item->amount,
            'شركة الشحن' => optional($item->shippingCompany)->name,
            'ملاحظات' => $item->notes,
        ];
    });
}

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'رقم التحصيل',
            'شركة الشحن',
            'المبلغ',
            'تاريخ التحصيل',
            'ملاحظات',
            'تم بواسطة',
            'تاريخ الإنشاء'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($collection): array
    {
        return [
            $collection->id,
            $collection->shippingCompany->name ?? 'غير محدد',
            $collection->amount,
            $collection->collection_date->format('Y-m-d'),
            $collection->notes ?? '',
            $collection->createdBy->name ?? 'غير محدد',
            $collection->created_at->format('Y-m-d H:i')
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
