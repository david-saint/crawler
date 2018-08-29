<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{

    /**
     * pages for this ad
     * 
     * @return [type] [description]
     */
    public function pages()
    {
    	return $this->belongsToMany('App\Page');
    }
}
