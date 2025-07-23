<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingCompany
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property int|null $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @package App\Models
 */
class ShippingCompany extends Model
{
	protected $table = 'shipping_companies';

	protected $casts = [
		'is_active' => 'int'
	];

	protected $fillable = [
		'name',
		'contact_person',
		'phone',
		'email',
		'address',
		'is_active'
	];
}
