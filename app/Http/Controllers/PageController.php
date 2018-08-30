<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::latest()->paginate(20);

        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'link' => 'url|required'
        ]);

        $page = new Page;
        $page->link = $request->link;
        $page->slug = str_random(25);

        $page->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return view('pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $page->load('ads');
        
        return view('pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->validate($request, [
            'link' => 'url|required',
            'title' => 'required',
            'slug' => [
                    'required',
                    Rule::unique('pages')->ignore($page->slug, 'slug')
                ]
        ]);

        $page->link = $request->link;
        $page->title = $request->title;
        $page->slug = $request->slug;

        $page->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->back();
    }

    public function addAd(Request $request, Page $page)
    {
        $this->validate($request, [
            'ad_id' => 'required|numeric'
        ]);

        DB::table('ad_page')->insert(
            ['ad_id' => $request->get('ad_id'), 'page_id' => $page->id ]
        );

        return redirect("/pages/$page->slug/edit");
    }
}
