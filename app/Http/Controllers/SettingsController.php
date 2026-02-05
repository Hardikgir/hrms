<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Settings index – only for users who can access at least one settings section.
     */
    public function index(Request $request)
    {
        if (! $request->user()->can('manage expense categories') && ! $request->user()->can('manage asset types')) {
            abort(403, 'You do not have access to settings.');
        }

        return view('settings.index');
    }
}
