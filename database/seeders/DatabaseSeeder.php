<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Event Management System with Topics and Speakers
        $this->call([
            EventSeeder::class,
        ]);

        $this->command->info('✅ All seeders completed successfully!');
        $this->command->info('📊 Database populated with:');
        $this->command->info('   - 3 Events (2024, 2025, 2026) with full content');
        $this->command->info('   - 8 Topics (linked to events)');
        $this->command->info('   - 9 Speakers (linked to topics - showing one-to-many)');
        $this->command->info('   - 8 Event Themes');
        $this->command->info('   - 5 Sponsors');
        $this->command->info('   - 6 FAQs');
        $this->command->info('   - Multiple Programmes, Media, Galleries, and Attendances');
    }
}
