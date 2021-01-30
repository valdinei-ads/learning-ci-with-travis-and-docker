<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
     /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Genre::factory()->times(50)->create();
    }
}
