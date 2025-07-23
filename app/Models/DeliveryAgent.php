<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeliveryAgent
 * 
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $national_id
 * @property int|null $user_id
 * @property int $shipping_company_id
 * @property int|null $max_edit_count
 * @property bool|null $is_active
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Collection[] $collections
 * @property Collection|Shipment[] $shipments
 *
 * @package App\Models
 */
class DeliveryAgent extends Model
{
	protected $table = 'delivery_agents';

	protected $casts = [
		'user_id' => 'int',
		'shipping_company_id' => 'int',
		'max_edit_count' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'name',
		'phone',
		'email',
		'address',
		'national_id',
		'user_id',
		'shipping_company_id',
		'max_edit_count',
		'is_active',
		'notes'
	];

	public function collections()
	{
		return $this->hasMany(Collection::class);
	}

	public function shipments()
	{
		return $this->hasMany(Shipment::class);
	}
}
