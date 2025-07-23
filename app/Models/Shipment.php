<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shipment
 * 
 * @property int $id
 * @property int|null $shipping_company_id
 * @property int|null $delivery_agent_id
 * @property string|null $delivery_agent_name
 * @property int|null $status_id
 * @property string|null $tracking_number
 * @property string|null $customer_name
 * @property string|null $customer_phone
 * @property string|null $customer_address
 * @property string|null $product_name
 * @property string|null $product_description
 * @property int|null $quantity
 * @property float|null $cost_price
 * @property float|null $selling_price
 * @property Carbon|null $shipping_date
 * @property Carbon|null $delivery_date
 * @property Carbon|null $return_date
 * @property Carbon|null $print_date
 * @property string|null $notes
 * @property string|null $agent_notes
 * @property int|null $edit_count
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property float|null $total_amount
 * @property string|null $color
 * @property string|null $size
 * @property string|null $governorate
 * @property float|null $shipping_price
 * @property int|null $product_id
 * @property string|null $shipping_company
 * @property bool $is_printed
 * 
 * @property DeliveryAgent|null $delivery_agent
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Shipment extends Model
{
	protected $table = 'shipments';

	protected $casts = [
		'shipping_company_id' => 'int',
		'delivery_agent_id' => 'int',
		'status_id' => 'int',
		'quantity' => 'int',
		'cost_price' => 'float',
		'selling_price' => 'float',
		'shipping_date' => 'datetime',
		'delivery_date' => 'datetime',
		'return_date' => 'datetime',
		'print_date' => 'datetime',
		'edit_count' => 'int',
		'total_amount' => 'float',
		'shipping_price' => 'float',
		'product_id' => 'int',
		'is_printed' => 'bool'
	];

	protected $fillable = [
		'shipping_company_id',
		'delivery_agent_id',
		'delivery_agent_name',
		'status_id',
		'tracking_number',
		'customer_name',
		'customer_phone',
		'customer_address',
		'product_name',
		'product_description',
		'quantity',
		'cost_price',
		'selling_price',
		'shipping_date',
		'delivery_date',
		'return_date',
		'print_date',
		'notes',
		'agent_notes',
		'edit_count',
		'total_amount',
		'color',
		'size',
		'governorate',
		'shipping_price',
		'product_id',
		'shipping_company',
		'is_printed'
	];

	public function delivery_agent()
	{
		return $this->belongsTo(DeliveryAgent::class);
	}

	public function products()
	{
		return $this->belongsToMany(Product::class, 'shipment_product')
					->withPivot('id', 'color', 'size', 'quantity', 'price')
					->withTimestamps();
	}
}
