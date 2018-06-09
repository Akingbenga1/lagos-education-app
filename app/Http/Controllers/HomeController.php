<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return View::make('home')
                     ->with(array( 'MyBreadCrumb' => '',
                                    'Title' => 'Welcome to Ijaye Housing Estate  Estate Senior Secondary Grammar School',
                 ));
    }

    public function index()
    {
        return View::make('home')
            ->with(array( 'MyBreadCrumb' => '',
                'Title' => 'Welcome to Ijaye Housing Estate  Estate Senior Secondary Grammar School',
            ));
    }


}
