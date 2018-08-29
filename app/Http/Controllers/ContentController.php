<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use App\Processors\Processor;

class ContentController extends Controller
{
    /**
     * get the page's content
     * 
     * @param  Page   $page [description]
     * @return [type]       [description]
     */
    public function getPage(Page $page)
    {
    	return (new Processor($page))->process();

        // return redirect("/{$page->slug}");
    }

    /**
     * Process a link request
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function processLink(Request $request)
    {
    	$exists = Page::where('link', $request->url)->first();

    	if ($exists)
    		return redirect("/{$exists->slug}");

    	$page = new Page;
    	$page->link = $request->url;
    	$page->slug = str_random(25);

    	$page->save();

    	return redirect("/f/{$page->slug}");
    }

    /**
     * Show a frame page
     * 
     * @param  Page   $page [description]
     * @return [type]       [description]
     */
    public function framePage(Page $page)
    {
        return view('frame', compact('page'));
    }
}
