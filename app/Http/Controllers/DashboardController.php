<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function show(Request $request): View
    {
        $variableToView = [];
        return view('page.dashboard')->with(compact($variableToView));
    }
}
