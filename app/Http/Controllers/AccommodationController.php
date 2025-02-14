<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::orderBy('order')
            ->orderBy('zone')
            ->orderBy('distance')
            ->get()
            ->groupBy('zone');

        return view('accommodations', compact('accommodations'));
    }
}
