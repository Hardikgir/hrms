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
     * Settings index – only for Super Admin.
     */
    public function index(Request $request)
    {
        if (! $request->user()->hasRole('Super Admin')) {
            abort(403, __('messages.settings_access_denied'));
        }

        return view('settings.index');
    }
}
