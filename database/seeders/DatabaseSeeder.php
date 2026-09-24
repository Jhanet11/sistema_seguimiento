<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Para crear la cuenta inicial ejecuta: php artisan edessi:admin. La demo optativa usa --class=DemoSeeder en una base local vacía.');
    }
}
