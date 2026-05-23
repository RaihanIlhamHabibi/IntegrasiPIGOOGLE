<?php

namespace App\Services;

use App\Models\GoogleCalendarEvent;
use App\Models\User;
use App\Services\Concerns\ManagesGoogleClient;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\ConferenceSolutionKey;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    use ManagesGoogleClient;

    protected Client $client;
    protected User $user;

    public function __construct(User $user)
    {
        $this->user   = $user;
        $this->client = $this->makeGoogleClient($user, [
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
        ]);
    }

    public function createEvent(
        string $title,
        ?string $description,
        string $startTime,
        string $endTime,
        ?string $location = null,
        bool $addMeet = false,
        string $calendarId = 'primary'
    ): ?GoogleCalendarEvent {
        try {
            if (!$this->user->googleToken) {
                Log::warning("User {$this->user->id} has no Google token. Skipping createEvent.");
                return null;
            }

            // ── 1. Insert event ke Google Calendar User A (creator) ──
            $service      = new Calendar($this->client);
            $event        = $this->buildEvent($title, $description, $startTime, $endTime, $location, $addMeet);
            $createdEvent = $service->events->insert($calendarId, $event, [
                'conferenceDataVersion' => 1,
            ]);

            $savedEvent = GoogleCalendarEvent::create([
                'user_id'           => $this->user->id,
                'google_event_id'   => $createdEvent->getId(),
                'event_title'       => $title,
                'event_description' => $description,
                'event_start'       => $this->parseDateTime($startTime),
                'event_end'         => $this->parseDateTime($endTime),
                'location'          => $location,
                'calendar_id'       => $calendarId,
                'hangout_link'      => $createdEvent->getHangoutLink(),
            ]);

            // ── 2. Broadcast ke user LAIN yang sudah connect Google ──
            $otherUsers = User::where('id', '!=', $this->user->id)
                ->whereHas('googleToken')
                ->get();

            foreach ($otherUsers as $otherUser) {
                try {
                    $targetClient = $this->makeGoogleClient($otherUser, [
                        'https://www.googleapis.com/auth/calendar',
                        'https://www.googleapis.com/auth/calendar.events',
                    ]);

                    if (!$targetClient->getAccessToken()) {
                        Log::warning("Skipping user {$otherUser->id}: no valid access token.");
                        continue;
                    }

                    $this->insertEventForUser(
                        $otherUser,
                        $title,
                        $description,
                        $startTime,
                        $endTime,
                        $location,
                        $addMeet,
                        $calendarId
                    );

                } catch (\Exception $e) {
                    Log::warning("Failed to insert event for user {$otherUser->id}: " . $e->getMessage());
                }
            }

            return $savedEvent;

        } catch (\Exception $e) {
            Log::error('Google Calendar event creation failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function insertEventForUser(
        User $targetUser,
        string $title,
        ?string $description,
        string $startTime,
        string $endTime,
        ?string $location,
        bool $addMeet = false,
        string $calendarId = 'primary'
    ): void {
        $targetClient = $this->makeGoogleClient($targetUser, [
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
        ]);

        if (!$targetClient->getAccessToken()) {
            Log::warning("Skipping user {$targetUser->id}: no valid access token.");
            return;
        }

        $service      = new Calendar($targetClient);
        $event        = $this->buildEvent($title, $description, $startTime, $endTime, $location, $addMeet);
        $createdEvent = $service->events->insert($calendarId, $event, [
            'conferenceDataVersion' => 1,
        ]);

        GoogleCalendarEvent::create([
            'user_id'           => $targetUser->id,
            'google_event_id'   => $createdEvent->getId(),
            'event_title'       => $title,
            'event_description' => $description,
            'event_start'       => $this->parseDateTime($startTime),
            'event_end'         => $this->parseDateTime($endTime),
            'location'          => $location,
            'calendar_id'       => $calendarId,
            'hangout_link'      => $createdEvent->getHangoutLink(),
        ]);

        Log::info("Event inserted for user {$targetUser->id} ({$targetUser->email})");
    }

    public function updateEvent(
        string $googleEventId,
        string $title,
        ?string $description,
        string $startTime,
        string $endTime,
        ?string $location = null,
        string $calendarId = 'primary'
    ): bool {
        try {
            if (!$this->user->googleToken) {
                return false;
            }

            $service = new Calendar($this->client);
            $event   = $service->events->get($calendarId, $googleEventId);
            $event->setSummary($title);
            $event->setDescription($description);
            $event->setLocation($location);
            $event->setStart($this->dateTimeObject($startTime));
            $event->setEnd($this->dateTimeObject($endTime));

            $service->events->update($calendarId, $googleEventId, $event);

            GoogleCalendarEvent::where('google_event_id', $googleEventId)
                ->where('user_id', $this->user->id)
                ->update([
                    'event_title'       => $title,
                    'event_description' => $description,
                    'event_start'       => $this->parseDateTime($startTime),
                    'event_end'         => $this->parseDateTime($endTime),
                    'location'          => $location,
                ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar event update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent(string $googleEventId, string $calendarId = 'primary'): bool
    {
        try {
            if (!$this->user->googleToken) {
                return false;
            }

            $service = new Calendar($this->client);
            $service->events->delete($calendarId, $googleEventId);

            GoogleCalendarEvent::where('google_event_id', $googleEventId)
                ->where('user_id', $this->user->id)
                ->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar event deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    public function listEvents(string $calendarId = 'primary', int $maxResults = 10): array
    {
        try {
            if (!$this->user->googleToken) {
                return [];
            }

            $service = new Calendar($this->client);
            $results = $service->events->listEvents($calendarId, [
                'maxResults'   => $maxResults,
                'orderBy'      => 'startTime',
                'singleEvents' => true,
                'timeMin'      => now()->toRfc3339String(),
            ]);

            return $results->getItems() ?? [];
        } catch (\Exception $e) {
            Log::error('Google Calendar list failed: ' . $e->getMessage());
            return [];
        }
    }

    protected function buildEvent(
        string $title,
        ?string $description,
        string $startTime,
        string $endTime,
        ?string $location,
        bool $addMeet = false
    ): Event {
        $event = new Event();
        $event->setSummary($title);
        $event->setDescription($description);
        $event->setLocation($location);
        $event->setStart($this->dateTimeObject($startTime));
        $event->setEnd($this->dateTimeObject($endTime));

        // Generate Google Meet link otomatis
        if ($addMeet) {
            $solutionKey = new ConferenceSolutionKey();
            $solutionKey->setType('hangoutsMeet');

            $createRequest = new CreateConferenceRequest();
            $createRequest->setRequestId(uniqid());
            $createRequest->setConferenceSolutionKey($solutionKey);

            $conferenceData = new ConferenceData();
            $conferenceData->setCreateRequest($createRequest);

            $event->setConferenceData($conferenceData);
        }

        return $event;
    }

    protected function dateTimeObject(string $datetime): EventDateTime
    {
        $dateTime = new EventDateTime();
        $dateTime->setDateTime($this->toRfc3339($datetime));
        $dateTime->setTimeZone(config('app.timezone', 'UTC'));

        return $dateTime;
    }

    protected function toRfc3339(string $datetime): string
    {
        return $this->parseDateTime($datetime)->toRfc3339String();
    }

    protected function parseDateTime(string $datetime): Carbon
    {
        return Carbon::parse($datetime);
    }
}