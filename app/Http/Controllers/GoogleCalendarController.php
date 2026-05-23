<?php

namespace App\Http\Controllers;

use App\Models\GoogleCalendarEvent;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GoogleCalendarController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $events = $user->googleCalendarEvents()->orderBy('event_start')->paginate(15);

        return view('google-calendar.index', compact('events'));
    }

    public function create(): View
    {
        return view('google-calendar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
        ]);

        try {
            $user = Auth::user();

            $calendarService = new GoogleCalendarService($user);
            $event = $calendarService->createEvent(
                $request->input('title'),
                $request->input('description'),
                $request->input('start_time'),
                $request->input('end_time'),
                $request->input('location')
            );

            if ($event) {
                return redirect()->route('google-calendar.index')->with('success', 'Event created successfully!');
            }

            return redirect()->back()->with('error', 'Failed to create event.');
        } catch (\Exception $e) {
            Log::error('Event creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the event.');
        }
    }

    public function edit($id): View
    {
        $user = Auth::user();
        $event = GoogleCalendarEvent::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('google-calendar.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
        ]);

        try {
            $user = Auth::user();
            $event = GoogleCalendarEvent::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $calendarService = new GoogleCalendarService($user);
            if ($calendarService->updateEvent(
                $event->google_event_id,
                $request->input('title'),
                $request->input('description'),
                $request->input('start_time'),
                $request->input('end_time'),
                $request->input('location')
            )) {
                return redirect()->route('google-calendar.index')->with('success', 'Event updated successfully!');
            }

            return redirect()->back()->with('error', 'Failed to update event.');
        } catch (\Exception $e) {
            Log::error('Event update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the event.');
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::user();
            $event = GoogleCalendarEvent::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $calendarService = new GoogleCalendarService($user);
            if ($calendarService->deleteEvent($event->google_event_id)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Event deleted successfully!',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Event delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the event.',
            ], 500);
        }
    }

    public function list()
    {
        try {
            $user = Auth::user();

            if (!$user->googleToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google account not connected.',
                ], 403);
            }

            $calendarService = new GoogleCalendarService($user);
            $events = $calendarService->listEvents('primary', 50);

            return response()->json([
                'success' => true,
                'events' => $events,
            ]);
        } catch (\Exception $e) {
            Log::error('List events error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events.',
            ], 500);
        }
    }
}
