<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	
    /**
     * The content of the page
     * 
     * @return [type] [description]
     */
    public function content()
    {
    	return $this->hasOne('App\Content');
    }

    /**
     * The ads on the page
     * 
     * @return [type] [description]
     */
    public function ads()
    {
        return $this->belongsToMany('App\Ad');
    }

    /**
     * The rooute key for this model
     * 
     * @return [type] [description]
     */
    public function getRouteKeyName()
	{
	    return 'slug';
	}
}
