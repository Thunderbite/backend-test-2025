<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('backstage.dashboard.index');
    }
}
