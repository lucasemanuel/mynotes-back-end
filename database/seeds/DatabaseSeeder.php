<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        factory(\App\User::class, 4)->create()->each(function ($user) {
            $user->notes()->createMany(
                factory(\App\Note::class, 3)->make()->toArray()
            );
        });
    }
}
