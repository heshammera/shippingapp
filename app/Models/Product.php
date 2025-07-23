<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $price
 * @property string|null $colors
 * @property string|null $sizes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property float $cost_price
 * 
 * @property Collection|ProductPrice[] $product_prices
 * @property Collection|Shipment[] $shipments
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'price' => 'int',
		'cost_price' => 'float'
	];

	protected $fillable = [
		'name',
		'price',
		'colors',
		'sizes',
		'cost_price'
	];

	public function product_prices()
	{
		return $this->hasMany(ProductPrice::class);
	}

	public function shipments()
	{
		return $this->belongsToMany(Shipment::class, 'shipment_product')
					->withPivot('id', 'color', 'size', 'quantity', 'price')
					->withTimestamps();
	}
}
