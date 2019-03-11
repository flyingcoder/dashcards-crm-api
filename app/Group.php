<?php

namespace App;

use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Group extends Role
{
	use SearchableTrait,
		SoftDeletes,
		Sluggable,
        LogsActivity;

    protected $table = 'roles';

    protected static $logAttributes = ['name'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A group has been {$eventName}";
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

	/**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'roles.name' => 10,
            'roles.id' => 8,
            'roles.description' => 5
        ]
    ];


   	public function company()
   	{
   		return $this->belongsTo(Company::class);
   	}


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
}
