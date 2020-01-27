<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Models\Eloquent\Permission as BasePermission;

class Permission extends BasePermission
{
	/**
	 * each permission might have one inherited permisision
	 * @return \App\Permission | null
	 */
	public function parent() {
		return $this->belongsTo(static::class, 'inherit_id');
	}

	/**
	 * each permission might have multiple children
	 * @return Permission | null
	 */
	public function children() {
		return $this->hasMany(static::class, 'inherit_id');
	}

	/**
	 * merge slug from parent permission with child permission, overriding parent with child
	 * @return string
	 */
	public function getCapabilityAttribute($value)
    {
    	$extended = is_array($this->slug) ?  $this->slug : json_decode($this->slug, true);
    	$base = is_array($this->parent->slug) ?  $this->parent->slug : json_decode($this->parent->slug, true);
        return array_merge($base, $extended);
    }
}
