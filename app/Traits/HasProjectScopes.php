<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasProjectScopes
{

    /**
     * Custom scope for getting all project with search
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchProjects(Builder $query, $keyword)
    {
        $table = 'projects';
        /*return $query->where(function ($query) use ($keyword, $table) {
                $query->where("{$table}.title", "like", "%{$keyword}%")
                      ->orWhere("client.first_name", "like", "%{$keyword}%")
                      ->orWhere("client.last_name", "like", "%{$keyword}%")
                      ->orWhere("services.name", "like", "%{$keyword}%")
                      ->orWhere("{$table}.status", "like", "%{$keyword}%")
                      ->orWhere("manager.first_name", "like", "%{$keyword}%")
                      ->orWhere("manager.last_name", "like", "%{$keyword}%");
          });*/
        return $query->where(function ($query) use ($keyword, $table) {
            $query->where("{$table}.title", "like", "%{$keyword}%")
                ->orWhere("{$table}.status", "like", "%{$keyword}%")
                ->orWhereHas("projectClient.user", function (Builder $query) use ($keyword) {
                    $query->where('first_name', "like", "%{$keyword}%")
                        ->orWhere('last_name', "like", "%{$keyword}%");
                })
                ->orWhereHas("projectService", function (Builder $query) use ($keyword) {
                    $query->where('name', "like", "%{$keyword}%");
                })
                ->orWhereHas("projectManager.user", function (Builder $query) use ($keyword) {
                    $query->where('first_name', "like", "%{$keyword}%")
                        ->orWhere('last_name', "like", "%{$keyword}%");
                });
        });
    }

    /**
     * Custom scope for getting project that can be view by users that part of the projects
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param integer $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProjectForUserInvolve($query, $user_id = null)
    {
        $user_id = $user_id ?? auth()->user()->id;
        return $query->whereHas('projectAllMembers', function (Builder $query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
    }

}