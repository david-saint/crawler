<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::latest()->get()->take(10);

        $ads = Ad::all()->take(10);

        $with = ['pages' => $pages, 'ads' => $ads];

        return view('home', $with);
    }
}
