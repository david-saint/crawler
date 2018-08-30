<?php

namespace App\Http\Controllers;

use App\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ad::paginate(20);

        return view('ads.index', compact('ads'));
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
            'name' => 'required',
            'url' => 'required|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $image = $request->file('image');

        if (!$image)
            return 'No Image';

        // set the image name to something random
        $image_name = rand(1000000, 9999999) . $image->getClientOriginalName();
        // move the image to the images folder
        $image->move('images', $image_name);
        // create the path for the db
        $image_path = "/images/$image_name";

        $ad = Ad::create($request->all());

        return redirect()->route('ads.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ad $ad)
    {
        return view('ads.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ad $ad)
    {
        $this->validate($request, [
            'name' => 'required',
            'url' => 'required|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $image = $request->file('image');

        if (!$image)
            return 'No Image';

        // set the image name to something random
        $image_name = rand(1000000, 9999999) . $image->getClientOriginalName();
        // move the image to the images folder
        $image->move('images', $image_name);
        // create the path for the db
        $image_path = "/images/$image_name";

        $ad->name = $request->name;
        $ad->url = $request->url;
        $ad->image = $request->image;

        $ad->save();

        return redirect()->route('ads.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();

        return redirect()->back();
    }


    public function autocomplete(Request $request)
    {
        if ($request->query) {

            $q = $request->get('query');

            $data = Ad::search($q)->get();

            $output = '<ul class="dropdown-menu" style="display: block; position: relative">';

            foreach ($data as $row) {
                $output .= '<li><a href="#" class="adid" data-adid="'. $row->id .'">'. $row->name .'</a></li>';
            }

            $output .= '</ul>';

            return $output;
        }
    }

}
