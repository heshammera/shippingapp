<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShipmentProduct
 * 
 * @property int $id
 * @property int $shipment_id
 * @property int $product_id
 * @property string $color
 * @property string $size
 * @property int $quantity
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Shipment $shipment
 *
 * @package App\Models
 */
class ShipmentProduct extends Model
{
	protected $table = 'shipment_product';

	protected $casts = [
		'shipment_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'shipment_id',
		'product_id',
		'color',
		'size',
		'quantity',
		'price'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function shipment()
	{
		return $this->belongsTo(Shipment::class);
	}
}
