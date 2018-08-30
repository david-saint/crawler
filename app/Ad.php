<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url', 'image',
    ];

    /**
     * pages for this ad
     * 
     * @return [type] [description]
     */
    public function pages()
    {
    	return $this->belongsToMany('App\Page');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $value 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $value)
    {
        return $query->where('name', 'LIKE', "%$value%");
    }
}
