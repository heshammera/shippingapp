<?php

namespace App\Imports;

use App\Models\Shipment;
use App\Models\ShipmentStatus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ShipmentsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $shippingCompanyId;
    protected $defaultStatusId;

    /**
     * Constructor
     */
    public function __construct($shippingCompanyId)
    {
        $this->shippingCompanyId = $shippingCompanyId;
        // الحصول على الحالة الافتراضية ()
        $this->defaultStatusId = 37;
    }

    /**
     * تحويل كل صف في ملف Excel إلى نموذج Shipment
     */

public function model(array $row)
{
    // تجاهل الصف لو مفيهوش اسم العميل أو اسم المنتج
   $row = array_change_key_case($row, CASE_LOWER);

if (empty($row['customer_name']) && empty($row['fullname'])) {
    return null;
}

if (empty($row['product_name']) && empty($row['product name'])) {
    return null;
}


    // تحويل كل المفاتيح إلى lowercase

    // استخراج القيم
    $name         = $row['customer_name'] ?? $row['fullname'] ?? null;
    $phone        = $row['customer_phone'] ?? $row['phone'] ?? null;
    $altPhone     = $row['alt phone'] ?? null;
    $address      = $row['customer_address'] ?? $row['address'] ?? null;
    $product      = $row['product_name'] ?? $row['product name'] ?? null;
    $description  = $row['product_description'] ?? $row['product description'] ?? null;
    $color        = $row['variant'] ?? null;

    // الكمية — تنظيف وتحقق
    $qtyRaw = $row['quantity'] ?? $row['qty'] ?? 1;
    $qtyClean = preg_replace('/[^\d]/', '', $qtyRaw);
    $qty = is_numeric($qtyClean) && intval($qtyClean) > 0 ? intval($qtyClean) : 1;

    // الأسعار
    
    
    
    $cost = $row['cost_price'] ?? $row['item price'] ?? 0;
$priceRaw = $row['selling_price'] ?? $row['product cost'] ?? 0;
$priceClean = preg_replace('/[^\d.]/', '', $priceRaw); // شيل رموز أو نصوص
$price = is_numeric($priceClean) ? floatval($priceClean) : 0;


$totalRaw = $row['total_amount'] ?? $row['total cost'] ?? ($qty * $price);
$totalClean = preg_replace('/[^\d.]/', '', $totalRaw); // تنظيف
$totalAmount = is_numeric($totalClean) ? floatval($totalClean) : ($qty * $price);


    $shipping = $row['shipping price'] ?? $row['shipping cost'] ?? 0;

    $governorate = $row['governorate'] ?? $row['extra data'] ?? null;
    $note        = $row['notes'] ?? $row['city'] ?? null;

    // التاريخ
    $dateRaw = $row['shipping_date'] ?? $row['createdat'] ?? now();
    $shippingDate = is_numeric($dateRaw)
        ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRaw)
        : \Carbon\Carbon::parse($dateRaw);

    // إنشاء الشحنة
    return new Shipment([
        'tracking_number'     => $row['tracking_number'] ?? 'TR-' . uniqid(),
        'customer_name'       => $name,
        'customer_phone'      => $phone,
        'alternate_phone'     => $altPhone,
        'customer_address'    => $address,
        'product_name'        => $product,
        'product_description' => $description,
        'quantity'            => $qty,
        'cost_price'          => $cost,
        'shipping_price'      => $shipping,
        'governorate'         => $governorate,
        'shipping_company_id' => $this->shippingCompanyId,
        'delivery_agent_id'   => null,
        'status_id'           => 37,
        'shipping_date'       => $shippingDate,
        'notes'               => $note,
        'edit_count'          => 0,
        'color'               => $color,
        'selling_price'       => $price,
        'total_amount'        => $totalAmount,

    ]);
}

public function rules(): array
{
    return [];
}



}
