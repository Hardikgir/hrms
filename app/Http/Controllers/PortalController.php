<?php

namespace App\Http\Controllers;

use App\Services\PortalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show portal selection page when user has multiple portals.
     */
    public function select(Request $request, PortalService $portalService)
    {
        $user = $request->user();
        $portals = $portalService->getAvailablePortalsForUser($user);

        if (count($portals) === 0) {
            return view('auth.no-portal-access');
        }

        if (count($portals) === 1) {
            Session::put('portal', $portals[0]);
            return redirect()->route($portalService->getDashboardRouteForPortal($portals[0]));
        }

        return view('auth.choose-portal', [
            'portals' => $portals,
            'portalService' => $portalService,
        ]);
    }

    /**
     * Set the current portal and redirect to its dashboard.
     */
    public function enter(Request $request, PortalService $portalService)
    {
        $request->validate(['portal' => 'required|string|in:employee,manager,admin']);

        $user = $request->user();
        if (! $portalService->userHasPortal($user, $request->input('portal'))) {
            return redirect()->route('portal.select')->with('error', __('messages.portal_access_denied'));
        }

        Session::put('portal', $request->input('portal'));
        return redirect()->route($portalService->getDashboardRouteForPortal($request->input('portal')));
    }
}
