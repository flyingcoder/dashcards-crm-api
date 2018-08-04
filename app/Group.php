<?php

namespace App;

use Kodeine\Acl\Models\Eloquent\Role;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Group extends Role
{
	use SearchableTrait,
		SoftDeletes,
		Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
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
}
