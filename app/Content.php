<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    
    /**
     * the page that owns the content
     * 
     * @return [type] [description]
     */
    public function page()
    {
    	return $this->belongsTo('App\Page');
    }
}
