<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventProgramme;
use App\Models\EventResource;
use App\Models\EventSummary;
use App\Models\EventTheme;
use App\Models\FAQ;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    public function overview(): array
    {
        $events = Event::query();
        $totalEvents = (clone $events)->count();
        $publishedEvents = (clone $events)->where('is_published', true)->count();

        $attendances = Attendance::query();
        $totalAttendances = (clone $attendances)->count();
        $checkedIn = (clone $attendances)->where('checked_in', true)->count();

        return [
            'events_total' => $totalEvents,
            'events_published' => $publishedEvents,
            'events_draft' => max(0, $totalEvents - $publishedEvents),
            'publish_rate' => $totalEvents > 0 ? round(($publishedEvents / $totalEvents) * 100) : 0,
            'speakers_total' => Speaker::count(),
            'speakers_key' => Speaker::where('is_key_speaker', true)->count(),
            'speakers_leaders' => Speaker::where('is_session_leader', true)->count(),
            'sponsors_total' => Sponsor::count(),
            'attendances_total' => $totalAttendances,
            'attendances_checked_in' => $checkedIn,
            'check_in_rate' => $totalAttendances > 0 ? round(($checkedIn / $totalAttendances) * 100) : 0,
            'users_total' => User::count(),
        ];
    }

    public function contentInventory(): array
    {
        return [
            'topics' => Topic::count(),
            'event_themes' => EventTheme::count(),
            'programmes' => EventProgramme::count(),
            'resources' => EventResource::count(),
            'media' => Media::count(),
            'galleries' => Gallery::count(),
            'faqs' => FAQ::count(),
            'summaries' => EventSummary::count(),
        ];
    }

    public function contentTotal(): int
    {
        $inventory = $this->contentInventory();

        return array_sum($inventory);
    }

    /**
     * @return array{labels: list<string>, data: list<int>}
     */
    public function eventsByYear(): array
    {
        $rows = Event::query()
            ->select('year', DB::raw('count(*) as total'))
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $rows->pluck('year')->map(fn ($y) => (string) $y)->all(),
            'data' => $rows->pluck('total')->map(fn ($n) => (int) $n)->all(),
        ];
    }

    /**
     * @return array{labels: list<string>, data: list<int>}
     */
    public function contentMix(): array
    {
        $inventory = $this->contentInventory();

        return [
            'labels' => [
                'Topic tracks',
                'Event themes',
                'Programme',
                'Documents',
                'Media',
                'Gallery',
                'FAQs',
                'Summaries',
            ],
            'data' => [
                $inventory['topics'],
                $inventory['event_themes'],
                $inventory['programmes'],
                $inventory['resources'],
                $inventory['media'],
                $inventory['galleries'],
                $inventory['faqs'],
                $inventory['summaries'],
            ],
        ];
    }

    /**
     * @return array{labels: list<string>, registered: list<int>, checked_in: list<int>}
     */
    public function attendanceByEvent(): array
    {
        $events = Event::query()
            ->withCount([
                'attendances',
                'attendances as checked_in_count' => fn ($q) => $q->where('checked_in', true),
            ])
            ->orderByDesc('year')
            ->limit(8)
            ->get();

        return [
            'labels' => $events->map(fn (Event $e) => $e->year ? "{$e->year}" : \Illuminate\Support\Str::limit($e->title, 18))->all(),
            'registered' => $events->pluck('attendances_count')->map(fn ($n) => (int) $n)->all(),
            'checked_in' => $events->pluck('checked_in_count')->map(fn ($n) => (int) $n)->all(),
        ];
    }

    /**
     * @return array{labels: list<string>, data: list<int>}
     */
    public function sponsorTiers(): array
    {
        $rows = Sponsor::query()
            ->select('tier', DB::raw('count(*) as total'))
            ->groupBy('tier')
            ->orderByDesc('total')
            ->get();

        if ($rows->isEmpty()) {
            return ['labels' => ['No sponsors'], 'data' => [0]];
        }

        return [
            'labels' => $rows->pluck('tier')->map(fn ($t) => filled($t) ? (string) $t : 'Unspecified')->all(),
            'data' => $rows->pluck('total')->map(fn ($n) => (int) $n)->all(),
        ];
    }

    public function eventsWithContentCounts(): Collection
    {
        return Event::query()
            ->withCount([
                'speakers',
                'topics',
                'sponsors',
                'programmes',
                'resources',
                'galleries',
                'attendances',
                'faqs',
                'media',
            ])
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();
    }
}
