<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function view()
    {
        // /main now sends each user to their own role dashboard
        return redirect()->route(auth()->user()->dashboardRoute());
    }
}