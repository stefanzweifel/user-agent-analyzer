<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{

    public function terms(Request $request)
    {
        return view('terms');
    }

}
