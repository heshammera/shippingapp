<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Collection
 * 
 * @property int $id
 * @property int|null $shipping_company_id
 * @property int|null $delivery_agent_id
 * @property int|null $amount
 * @property string|null $collection_date
 * @property string|null $notes
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * 
 * @property DeliveryAgent|null $delivery_agent
 *
 * @package App\Models
 */
class Collection extends Model
{
	protected $table = 'collections';

	protected $casts = [
		'shipping_company_id' => 'int',
		'delivery_agent_id' => 'int',
		'amount' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'shipping_company_id',
		'delivery_agent_id',
		'amount',
		'collection_date',
		'notes',
		'created_by'
	];

	public function delivery_agent()
	{
		return $this->belongsTo(DeliveryAgent::class);
	}
}
