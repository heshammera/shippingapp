<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShipmentStatus
 * 
 * @property int $id
 * @property int|null $sort_order
 * @property string|null $name
 * @property string|null $color
 * @property int|null $is_default
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $row_color
 *
 * @package App\Models
 */
class ShipmentStatus extends Model
{
	protected $table = 'shipment_statuses';

	protected $casts = [
		'sort_order' => 'int',
		'is_default' => 'int'
	];

	protected $fillable = [
		'sort_order',
		'name',
		'color',
		'is_default',
		'row_color'
	];
}
