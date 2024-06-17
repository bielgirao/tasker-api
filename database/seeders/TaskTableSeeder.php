<?php

namespace Database\Seeders;

use Database\Factories\TaskFactory;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        TaskFactory::new()->count(10)->create();
    }
}
