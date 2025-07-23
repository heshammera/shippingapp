<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * 
 * @property int|null $id
 * @property string|null $key
 * @property string|null $value
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @package App\Models
 */
class Setting extends Model
{
	protected $table = 'settings';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'id',
		'key',
		'value',
		'description'
	];
}
