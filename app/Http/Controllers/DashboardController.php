<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $redirect = session()->get('redirect');
        if ($redirect) {
            session()->remove('redirect');

            return redirect($redirect);
        }

        return Inertia::render('Dashboard');
    }
}
