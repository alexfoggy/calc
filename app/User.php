<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'admin_user';

	protected $fillable = [
		'name', 'email', 'password', 'login', 'admin_user_group_id', 'remember_token', 'root', 'img'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token'
	];

	public function group()
	{
		return $this->hasOne('App\Models\AdminUserGroup', 'id', 'admin_user_group_id');
	}

	public function objectsList()
	{
		return $this->hasMany('App\Models\GoodsItemId', 'admin_user_id', 'id');
	}

}
