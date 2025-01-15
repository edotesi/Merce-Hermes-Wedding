<?php

namespace App\Http\Controllers;

use App\Models\Ceremony;
use App\Models\Reception;
use App\Models\Party;

class ScheduleController extends Controller
{
    public function index()
    {
        $ceremony = Ceremony::first();
        $reception = Reception::first();
        $party = Party::first();

        return view('schedule', compact('ceremony', 'reception', 'party'));
    }
}
