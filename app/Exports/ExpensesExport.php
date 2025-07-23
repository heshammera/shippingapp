<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
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
        $query = Expense::with(['createdBy']);
        
        // تطبيق الفلاتر
        if (isset($this->filters['date_from']) && $this->filters['date_from']) {
            $query->where('expense_date', '>=', $this->filters['date_from']);
        }
        
        if (isset($this->filters['date_to']) && $this->filters['date_to']) {
            $query->where('expense_date', '<=', $this->filters['date_to']);
        }
        
        return $query->orderBy('expense_date', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'رقم المصروف',
            'العنوان',
            'المبلغ',
            'تاريخ المصروف',
            'ملاحظات',
            'تم بواسطة',
            'تاريخ الإنشاء'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->title,
            $expense->amount,
            $expense->expense_date->format('Y-m-d'),
            $expense->notes ?? '',
            $expense->createdBy->name ?? 'غير محدد',
            $expense->created_at->format('Y-m-d H:i')
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
