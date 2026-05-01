<?php

namespace App\Console\Commands;

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
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncRemoteEventData extends Command
{
    protected $signature = 'sync:remote-events
                            {--url= : Base URL of the remote site (default: REMOTE_EVENT_API_URL or https://eduevent.e-saloon.online)}
                            {--purge : Remove existing events, topics, and related rows before importing}';

    protected $description = 'Import events and related content from the remote Event Management API into the local database';

    public function handle(): int
    {
        $baseUrl = rtrim(
            $this->option('url') ?: (string) env('REMOTE_EVENT_API_URL', 'https://eduevent.e-saloon.online'),
            '/'
        );

        $this->info("Fetching from {$baseUrl} …");

        try {
            DB::transaction(function () use ($baseUrl): void {
                if ($this->option('purge')) {
                    $this->purgeLocalEventDomain();
                }

                $events = $this->fetchData($baseUrl, 'events');
                if ($events === []) {
                    $this->warn('Remote returned no events.');

                    return;
                }

                $bar = $this->output->createProgressBar(count($events));
                $bar->start();

                $eventDetails = [];

                foreach ($events as $summary) {
                    if (! is_array($summary) || empty($summary['id'])) {
                        $bar->advance();

                        continue;
                    }

                    $detail = $this->fetchData($baseUrl, 'events/' . $summary['id']);
                    if ($detail === []) {
                        $this->newLine();
                        $this->warn("Skipping event {$summary['id']}: detail response empty.");

                        $bar->advance();

                        continue;
                    }

                    $eventDetails[] = $detail;
                    $this->upsertEventRelations($detail);
                    $bar->advance();
                }

                $bar->finish();
                $this->newLine(2);

                $topics = $this->fetchData($baseUrl, 'topics');
                foreach ($topics as $topicRow) {
                    if (! is_array($topicRow) || empty($topicRow['id'])) {
                        continue;
                    }

                    $clean = Arr::except($topicRow, ['speakers', 'event']);
                    $this->upsertModel(new Topic, $clean);
                }

                foreach ($eventDetails as $detail) {
                    foreach ($detail['speakers'] ?? [] as $row) {
                        if (is_array($row) && ! empty($row['id'])) {
                            $this->upsertModel(new Speaker, $row);
                        }
                    }
                }
            });
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Sync finished. Set IMAGE_BASE_URL to the remote storage base if media paths should load from production (see .env.example).');

        return self::SUCCESS;
    }

    private function purgeLocalEventDomain(): void
    {
        $this->warn('Purging local events, topics, and related rows …');

        Speaker::query()->delete();
        Topic::query()->delete();
        Attendance::query()->delete();
        Gallery::query()->delete();
        Sponsor::query()->delete();
        Media::query()->delete();
        FAQ::query()->delete();
        EventResource::query()->delete();
        EventProgramme::query()->delete();
        EventTheme::query()->delete();
        EventSummary::query()->delete();
        Event::query()->delete();
    }

    /**
     * @return array<int, mixed>
     */
    private function fetchData(string $baseUrl, string $apiPath): array
    {
        $path = '/api/' . ltrim($apiPath, '/');
        $response = Http::timeout(120)
            ->acceptJson()
            ->get($baseUrl . $path);

        if (! $response->successful()) {
            throw new \RuntimeException("HTTP {$response->status()} for {$path}: {$response->body()}");
        }

        $json = $response->json();
        if (! is_array($json) || ! ($json['success'] ?? false)) {
            throw new \RuntimeException('Unexpected JSON for ' . $path);
        }

        $data = $json['data'] ?? [];

        return is_array($data) ? $data : [];
    }

    /**
     * @param  array<string, mixed>  $detail
     */
    /**
     * Persist event row and all relations except speakers (speakers reference topics).
     */
    private function upsertEventRelations(array $detail): void
    {
        $relations = [
            'summaries' => EventSummary::class,
            'themes' => EventTheme::class,
            'programmes' => EventProgramme::class,
            'resources' => EventResource::class,
            'faqs' => FAQ::class,
            'media' => Media::class,
            'sponsors' => Sponsor::class,
            'galleries' => Gallery::class,
            'attendances' => Attendance::class,
        ];

        $eventPayload = Arr::except($detail, array_merge(array_keys($relations), ['speakers']));
        $eventPayload = Arr::except($eventPayload, ['cover_image_url']);
        $this->upsertEventRecord($eventPayload);

        foreach ($relations as $key => $model) {
            foreach ($detail[$key] ?? [] as $row) {
                if (is_array($row) && ! empty($row['id'])) {
                    $this->upsertModel(new $model, $row);
                }
            }
        }
    }

    /**
     * @param  array<string, mixed>  $eventPayload
     */
    private function upsertEventRecord(array $eventPayload): void
    {
        $id = (string) $eventPayload['id'];
        $fillable = (new Event)->getFillable();
        $data = Arr::only($eventPayload, $fillable);

        if (array_key_exists('year', $data) && $data['year'] !== null && $data['year'] !== '') {
            $data['year'] = (int) $data['year'];
            Event::query()->where('year', $data['year'])->where('id', '!=', $id)->delete();
        }

        $model = Event::query()->find($id);
        if (! $model) {
            $model = new Event;
            $model->id = $id;
        }
        $model->fill($data);
        $model->save();
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function upsertModel(Model $prototype, array $row): void
    {
        $class = $prototype::class;
        $id = $row['id'];
        $fillable = $prototype->getFillable();
        $data = Arr::only($row, $fillable);

        foreach (['year', 'order'] as $intKey) {
            if (array_key_exists($intKey, $data) && $data[$intKey] !== null && $data[$intKey] !== '') {
                $data[$intKey] = (int) $data[$intKey];
            }
        }

        $model = $class::query()->find($id);
        if (! $model) {
            $model = new $class;
            $model->setAttribute($model->getKeyName(), $id);
        }
        $model->fill($data);
        $model->save();
    }
}
