<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Muestra la vista de mantenimiento.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('maintenance'); // Asegúrate de tener resources/views/maintenance.blade.php
    }
}
