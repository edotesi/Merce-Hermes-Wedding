<?php

namespace App\Http\Controllers;

use App\Models\Ceremony;
use App\Models\Reception;
use App\Models\WeddingInfo;

class InfoController extends Controller
{
    public function index()
    {
        $ceremony = Ceremony::first();
        $reception = Reception::first();
        $weddingInfo = WeddingInfo::first();

        return view('info', compact('ceremony', 'reception', 'weddingInfo'));
    }
}
