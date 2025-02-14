<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DressCodeController extends Controller
{
    public function index()
    {
        return view('dress-code');
    }
}
