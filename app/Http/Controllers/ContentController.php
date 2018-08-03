<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use App\Processors\Processor;

class ContentController extends Controller
{
    //
    public function getPage(Page $page)
    {
    	return (new Processor($page))->process();
    }

    public function processLink(Request $request)
    {
    	$exists = Page::where('link', $request->url)->first();

    	if ($exists)
    		return redirect("/{$exists->slug}");

    	$page = new Page;
    	$page->link = $request->url;
    	$page->slug = str_random(25);

    	$page->save();

    	return redirect("/{$page->slug}");
    }
}
