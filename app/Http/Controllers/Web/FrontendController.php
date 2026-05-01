<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FrontendController extends Controller
{
    private function formatDateTimeValue(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            $date = new \DateTime($value);
            return $date->format('M j, Y g:i A');
        } catch (\Exception) {
            return $value;
        }
    }

    private function decorateEventDates(array $event): array
    {
        $event['start_date_formatted'] = $this->formatDateTimeValue($event['start_date'] ?? null);
        $event['end_date_formatted'] = $this->formatDateTimeValue($event['end_date'] ?? null);

        if (isset($event['programmes']) && is_array($event['programmes'])) {
            foreach ($event['programmes'] as &$programme) {
                if (is_array($programme)) {
                    $programme['start_time_formatted'] = $this->formatDateTimeValue($programme['start_time'] ?? null);
                    $programme['end_time_formatted'] = $this->formatDateTimeValue($programme['end_time'] ?? null);
                }
            }
            unset($programme);
        }

        return $event;
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $value = trim($path);
        $imageBaseUrl = rtrim((string) config('app.image_base_url', ''), '/');

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            if ($imageBaseUrl !== '') {
                $urlPath = parse_url($value, PHP_URL_PATH);
                if (is_string($urlPath) && preg_match('#^/storage/(.+)$#', $urlPath, $matches)) {
                    return $this->joinPublicStorageUrl($imageBaseUrl, $matches[1]);
                }
            }

            return $value;
        }

        $trimmed = ltrim($value, '/');
        if ($imageBaseUrl === '') {
            $withStoragePrefix = str_starts_with($trimmed, 'storage/') ? $trimmed : 'storage/' . $trimmed;

            return '/' . $withStoragePrefix;
        }

        return $this->joinPublicStorageUrl($imageBaseUrl, $trimmed);
    }

    private function joinPublicStorageUrl(string $imageBaseUrl, string $pathWithinOrBesideStorage): string
    {
        $trimmed = ltrim($pathWithinOrBesideStorage, '/');
        $baseEndsWithStorage = preg_match('#/storage$#i', $imageBaseUrl) === 1;
        $relativePath = $baseEndsWithStorage ? preg_replace('#^storage/#i', '', $trimmed) : $trimmed;
        $joined = $baseEndsWithStorage
            ? $imageBaseUrl . '/' . $relativePath
            : $imageBaseUrl . '/storage/' . $relativePath;

        return preg_replace('#(?<!:)/{2,}#', '/', $joined);
    }

    private function withEventImageUrls(array $event): array
    {
        $event = $this->decorateEventDates($event);
        $event['cover_image'] = $this->resolveImageUrl($event['cover_image'] ?? null) ?? ($event['cover_image'] ?? null);
        $event['cover_image_url'] = $this->resolveImageUrl($event['cover_image_url'] ?? null) ?? ($event['cover_image_url'] ?? null);

        if (isset($event['speakers']) && is_array($event['speakers'])) {
            foreach ($event['speakers'] as &$speaker) {
                if (is_array($speaker)) {
                    $speaker['photo'] = $this->resolveImageUrl($speaker['photo'] ?? null) ?? ($speaker['photo'] ?? null);
                }
            }
            unset($speaker);
        }

        if (isset($event['sponsors']) && is_array($event['sponsors'])) {
            foreach ($event['sponsors'] as &$sponsor) {
                if (is_array($sponsor)) {
                    $sponsor['logo'] = $this->resolveImageUrl($sponsor['logo'] ?? null) ?? ($sponsor['logo'] ?? null);
                }
            }
            unset($sponsor);
        }

        if (isset($event['resources']) && is_array($event['resources'])) {
            foreach ($event['resources'] as &$resource) {
                if (is_array($resource)) {
                    $resource['url'] = $this->resolveImageUrl($resource['url'] ?? null) ?? ($resource['url'] ?? null);
                    $resource['file_path'] = $this->resolveImageUrl($resource['file_path'] ?? null) ?? ($resource['file_path'] ?? null);
                }
            }
            unset($resource);
        }

        if (isset($event['galleries']) && is_array($event['galleries'])) {
            foreach ($event['galleries'] as &$gallery) {
                if (is_array($gallery)) {
                    $gallery['url'] = $this->resolveImageUrl($gallery['url'] ?? null) ?? ($gallery['url'] ?? null);
                    $gallery['image_path'] = $this->resolveImageUrl($gallery['image_path'] ?? null) ?? ($gallery['image_path'] ?? null);
                }
            }
            unset($gallery);
        }

        return $event;
    }

    private function apiGet(Request $request, string $path, array $query = []): array
    {
        $subRequest = Request::create(
            '/api/' . ltrim($path, '/'),
            'GET',
            $query,
            $request->cookies->all(),
            [],
            array_merge($request->server->all(), [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ])
        );

        $response = app()->handle($subRequest);
        if (! $response instanceof JsonResponse || $response->getStatusCode() >= 400) {
            return [];
        }

        $payload = $response->getData(true);
        if (! is_array($payload) || ! ($payload['success'] ?? false)) {
            return [];
        }

        $data = $payload['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    /**
     * Normalise sponsors from API / eager-loaded payloads: always a sequential list of arrays.
     * Handles JSON quirks (digit-string keys), stdClass, and single-row responses.
     *
     * @param  mixed  $sponsors  List of sponsors or one associative sponsor row
     * @return list<array<string, mixed>>
     */
    private function normalizeEventSponsorsList(mixed $sponsors): array
    {
        if ($sponsors === null || $sponsors === []) {
            return [];
        }

        if ($sponsors instanceof \Illuminate\Contracts\Support\Arrayable) {
            $sponsors = $sponsors->toArray();
        }

        if (is_object($sponsors)) {
            $decoded = json_decode(json_encode($sponsors), true);
            $sponsors = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($sponsors)) {
            return [];
        }

        if (array_is_list($sponsors)) {
            $chunks = $sponsors;
        } else {
            $chunks = [];
            foreach ($sponsors as $val) {
                if (is_object($val)) {
                    $val = json_decode(json_encode($val), true);
                }
                if (is_array($val)) {
                    $chunks[] = $val;
                }
            }
            if ($chunks === []) {
                $chunks = [$sponsors];
            }
        }

        $out = [];
        foreach ($chunks as $row) {
            if (is_object($row)) {
                $row = json_decode(json_encode($row), true);
            }
            if (! is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            $id = trim((string) ($row['id'] ?? ''));
            if ($name === '' && $id === '') {
                continue;
            }
            $out[] = $row;
        }

        return $out;
    }

    private function decorateSponsorLogo(array $row): array
    {
        $row['logo'] = $this->resolveImageUrl($row['logo'] ?? null) ?? ($row['logo'] ?? null);

        return $row;
    }

    /**
     * Sponsor rows for one event from GET /api/sponsors?event_id=….
     *
     * @return list<array<string, mixed>>|null null when the sponsors index did not succeed
     */
    private function apiGetSponsorsForEvent(Request $request, string $eventId): ?array
    {
        $subRequest = Request::create(
            '/api/sponsors',
            'GET',
            ['event_id' => $eventId],
            $request->cookies->all(),
            [],
            array_merge($request->server->all(), [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ])
        );

        $response = app()->handle($subRequest);
        if (! $response instanceof JsonResponse || $response->getStatusCode() !== 200) {
            return null;
        }

        $payload = $response->getData(true);
        if (! is_array($payload) || ! ($payload['success'] ?? false) || ! array_key_exists('data', $payload)) {
            return null;
        }

        $rows = $this->normalizeEventSponsorsList($payload['data']);

        return $rows;
    }

    private function currentEvent(Request $request): array
    {
        $events = $this->apiGet($request, 'events');
        if ($events === []) {
            return [];
        }

        usort($events, fn ($a, $b) => ((int) ($b['year'] ?? 0)) <=> ((int) ($a['year'] ?? 0)));
        $current = collect($events)->first(fn ($event) => (bool) ($event['is_published'] ?? true));
        if (! is_array($current) || ! isset($current['id'])) {
            return [];
        }

        return $this->withEventImageUrls($this->apiGet($request, 'events/' . $current['id']));
    }

    public function home(Request $request)
    {
        $event = $this->currentEvent($request);
        $events = $this->apiGet($request, 'events', ['published_only' => 1]);
        $events = array_values(array_filter(array_map(
            fn ($item) => is_array($item) ? $this->withEventImageUrls($item) : null,
            $events
        )));
        usort($events, fn ($a, $b) => ((int) ($b['year'] ?? 0)) <=> ((int) ($a['year'] ?? 0)));

        $featuredEventId = $event['id'] ?? null;
        $eventsForCards = $featuredEventId === null
            ? $events
            : array_values(array_filter(
                $events,
                fn ($item) => ! is_array($item) || ($item['id'] ?? null) !== $featuredEventId
            ));

        $topics = $this->apiGet($request, 'topics');
        foreach ($topics as &$topic) {
            if (is_array($topic)) {
                $topic['topic_picture'] = $this->resolveImageUrl($topic['topic_picture'] ?? null) ?? ($topic['topic_picture'] ?? null);
            }
        }
        unset($topic);

        $resources = is_array($event['resources'] ?? null) ? $event['resources'] : [];
        if (count($resources) < 3) {
            $globalResources = $this->apiGet($request, 'resources');
            foreach ($globalResources as &$resource) {
                if (is_array($resource)) {
                    $resource['url'] = $this->resolveImageUrl($resource['url'] ?? null) ?? ($resource['url'] ?? null);
                    $resource['file_path'] = $this->resolveImageUrl($resource['file_path'] ?? null) ?? ($resource['file_path'] ?? null);
                }
            }
            unset($resource);

            $resources = array_values(array_filter(array_merge($resources, $globalResources), fn ($item) => is_array($item)));
            $unique = [];
            foreach ($resources as $resource) {
                $key = (string) ($resource['id'] ?? ($resource['title'] ?? uniqid('resource_', true)));
                $unique[$key] = $resource;
            }
            $resources = array_values($unique);
        }
        $speakers = is_array($event['speakers'] ?? null) ? $event['speakers'] : [];
        $sponsors = is_array($event['sponsors'] ?? null) ? $event['sponsors'] : [];
        $faqs = is_array($event['faqs'] ?? null) ? $event['faqs'] : [];

        usort($speakers, function ($a, $b) {
            $aKey = (bool) ($a['is_key_speaker'] ?? $a['key_speaker'] ?? false);
            $bKey = (bool) ($b['is_key_speaker'] ?? $b['key_speaker'] ?? false);
            $aLead = (bool) ($a['is_session_leader'] ?? $a['session_leader'] ?? false);
            $bLead = (bool) ($b['is_session_leader'] ?? $b['session_leader'] ?? false);
            return [$bKey, $bLead] <=> [$aKey, $aLead];
        });

        $eventTopicHighlights = [];
        if ($event !== []) {
            $eid = $event['id'];
            $eventTopicHighlights = array_values(array_filter($topics, static function ($row) use ($eid) {
                return is_array($row) && ($row['event_id'] ?? null) === $eid;
            }));
            usort($eventTopicHighlights, static fn ($a, $b) => strcmp((string) ($a['topic_date'] ?? ''), (string) ($b['topic_date'] ?? '')));
            $eventTopicHighlights = array_slice($eventTopicHighlights, 0, 4);
        }

        return view('frontend.pages.home', [
            'event' => $event,
            'events' => array_slice($eventsForCards, 0, 3),
            'speakers' => array_slice($speakers, 0, 3),
            'topics' => array_slice($topics, 0, 4),
            'event_topic_highlights' => $eventTopicHighlights,
            'resources' => array_slice($resources, 0, 3),
            'sponsors' => array_slice($sponsors, 0, 3),
            'faqs' => $faqs,
        ]);
    }

    public function events(Request $request)
    {
        $events = $this->apiGet($request, 'events', ['published_only' => 1]);
        $events = array_map(fn ($event) => is_array($event) ? $this->withEventImageUrls($event) : $event, $events);
        usort($events, fn ($a, $b) => ((int) ($b['year'] ?? 0)) <=> ((int) ($a['year'] ?? 0)));

        return view('frontend.pages.events', ['events' => $events]);
    }

    public function eventDetails(Request $request, string $id)
    {
        $event = $this->withEventImageUrls($this->apiGet($request, 'events/' . $id));
        abort_if($event === [], 404);

        $topics = [];
        if (! empty($event['topics']) && is_array($event['topics'])) {
            $topics = $event['topics'];
        } else {
            $topics = $this->apiGet($request, 'topics', ['event_id' => $id]);
        }
        foreach ($topics as &$topic) {
            if (is_array($topic) && isset($topic['speakers'])) {
                foreach ($topic['speakers'] as &$spk) {
                    if (is_array($spk)) {
                        $spk['photo'] = $this->resolveImageUrl($spk['photo'] ?? null) ?? ($spk['photo'] ?? null);
                    }
                }
                unset($spk);
            }
        }
        unset($topic);

        usort($topics, fn ($a, $b) => (int) ($a['order'] ?? 99) <=> (int) ($b['order'] ?? 99));

        $topicsByTitle = [];
        foreach ($topics as $topic) {
            if (is_array($topic) && ! empty($topic['title'])) {
                $topicsByTitle[mb_strtolower(trim($topic['title']))] = $topic;
            }
        }

        $sponsorsForEvent = $this->apiGetSponsorsForEvent($request, $id);
        if ($sponsorsForEvent !== null) {
            $event['sponsors'] = $sponsorsForEvent;
        } elseif (($fallback = $this->apiGet($request, 'sponsors', ['event_id' => $id])) !== []) {
            $event['sponsors'] = $fallback;
        }

        $normalizedSponsors = $this->normalizeEventSponsorsList($event['sponsors'] ?? []);
        foreach ($normalizedSponsors as &$sRow) {
            $sRow = $this->decorateSponsorLogo($sRow);
        }
        unset($sRow);
        $event['sponsors'] = $normalizedSponsors;

        return view('frontend.pages.event-details', [
            'event' => $event,
            'topics' => $topics,
            'topicsByTitle' => $topicsByTitle,
        ]);
    }

    public function topicDetail(Request $request, string $eventId, string $topicId)
    {
        $event = $this->withEventImageUrls($this->apiGet($request, 'events/' . $eventId));
        abort_if($event === [], 404);

        $topics = $this->apiGet($request, 'topics', ['event_id' => $eventId]);
        $topic = null;
        foreach ($topics as $row) {
            if (is_array($row) && ($row['id'] ?? null) === $topicId) {
                $topic = $row;
                break;
            }
        }
        abort_if($topic === null, 404);

        if (isset($topic['speakers'])) {
            foreach ($topic['speakers'] as &$spk) {
                if (is_array($spk)) {
                    $spk['photo'] = $this->resolveImageUrl($spk['photo'] ?? null) ?? ($spk['photo'] ?? null);
                }
            }
            unset($spk);
        }

        return view('frontend.pages.topic-detail', [
            'event' => $event,
            'topic' => $topic,
        ]);
    }

    public function speakers(Request $request)
    {
        $event = $this->currentEvent($request);
        $speakers = is_array($event['speakers'] ?? null) ? $event['speakers'] : [];

        return view('frontend.pages.speakers', ['speakers' => $speakers]);
    }

    public function topics(Request $request)
    {
        $topics = $this->apiGet($request, 'topics');
        foreach ($topics as &$topic) {
            if (is_array($topic)) {
                $topic['topic_picture'] = $this->resolveImageUrl($topic['topic_picture'] ?? null) ?? ($topic['topic_picture'] ?? null);
                $topic['topic_date_formatted'] = $this->formatDateTimeValue($topic['topic_date'] ?? null);
                if (isset($topic['speakers']) && is_array($topic['speakers'])) {
                    foreach ($topic['speakers'] as &$speaker) {
                        if (is_array($speaker)) {
                            $speaker['photo'] = $this->resolveImageUrl($speaker['photo'] ?? null) ?? ($speaker['photo'] ?? null);
                        }
                    }
                    unset($speaker);
                }
            }
        }
        unset($topic);
        usort($topics, fn ($a, $b) => ((int) ($b['event']['year'] ?? 0)) <=> ((int) ($a['event']['year'] ?? 0)));

        return view('frontend.pages.topics', ['topics' => $topics]);
    }

    public function resources(Request $request)
    {
        $resources = $this->apiGet($request, 'resources');
        foreach ($resources as &$resource) {
            if (is_array($resource)) {
                $resource['url'] = $this->resolveImageUrl($resource['url'] ?? null) ?? ($resource['url'] ?? null);
                $resource['file_path'] = $this->resolveImageUrl($resource['file_path'] ?? null) ?? ($resource['file_path'] ?? null);
            }
        }
        unset($resource);
        return view('frontend.pages.resources', ['resources' => $resources]);
    }

    public function gallery(Request $request)
    {
        $items = $this->apiGet($request, 'gallery');
        foreach ($items as &$item) {
            if (is_array($item)) {
                $item['url'] = $this->resolveImageUrl($item['url'] ?? null) ?? ($item['url'] ?? null);
                $item['image_path'] = $this->resolveImageUrl($item['image_path'] ?? null) ?? ($item['image_path'] ?? null);
            }
        }
        unset($item);
        return view('frontend.pages.gallery', ['items' => $items]);
    }
}
