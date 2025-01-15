<?php

namespace App\Http\Controllers;


use App\Models\WeddingInfo;

class InfoController extends Controller
{
    public function index()
    {
        $weddingInfo = WeddingInfo::first();

        return view('info', compact('weddingInfo'));
    }
}
