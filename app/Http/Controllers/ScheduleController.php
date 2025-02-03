<?php

namespace App\Http\Controllers;

use App\Models\Ceremony;
use App\Models\Reception;
use App\Models\Party;
use App\Models\Appetizer;
use App\Models\Feast;

class ScheduleController extends Controller
{
    public function index()
    {
        $ceremony = Ceremony::first();
        $reception = Reception::first();
        $appetizer = Appetizer::first();
        $feast = Feast::first();
        $party = Party::first();

        return view('schedule', compact('ceremony', 'reception', 'appetizer', 'feast', 'party'));
    }
}
