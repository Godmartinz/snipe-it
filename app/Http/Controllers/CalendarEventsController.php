<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Renders the unified calendar page. The heavy lifting (event
 * fetching, source hydration, per-row access checks) happens on the
 * companion API endpoint at /api/v1/calendar/events; this controller
 * just gates page access and returns the shell blade that the
 * FullCalendar JS bundle mounts into.
 */
class CalendarEventsController extends Controller
{
    public function index(Request $request): View
    {
        // Base gate mirrors the API endpoint (Api\CalendarEventsController):
        // viewer must be able to view AT LEAST ONE registered
        // HasCalendarEvents source model. Source list comes from
        // CalendarEvent::sourceModels() so a new adopter of the
        // trait picks up here automatically. Per-row scoping happens
        // inside the API endpoint, so a license-only admin lands
        // here and gets only their license events on the calendar.
        $viewer = $request->user();
        $canViewAnySource = false;
        foreach (CalendarEvent::sourceModels() as $sourceClass) {
            if ($viewer?->can('view', $sourceClass)) {
                $canViewAnySource = true;
                break;
            }
        }
        abort_unless($canViewAnySource, 403);

        return view('calendar.index');
    }
}
