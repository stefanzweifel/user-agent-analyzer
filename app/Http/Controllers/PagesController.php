<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function terms(Request $request)
    {
        return view('terms');
    }
}
