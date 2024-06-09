<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->has(Tag::factory(3), "tags")->has(Dish::factory(10), "dishes")->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tags = $user->tags()->get();
        $dishes = $user->dishes()->get();

        // Attach random one or two tags to each dish
        foreach ($dishes as $dish) {
            $dish->tags()->attach(
                $tags->random(rand(1, 2))->pluck('id')->toArray()
            );
        }
    }
}
