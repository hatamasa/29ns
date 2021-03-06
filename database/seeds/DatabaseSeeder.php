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
        $this->call(AreasSeeder::class);
        $this->call(StationsSeeder::class);

        if (env('APP_ENV') != 'prod') {
            $this->call(UsersSeeder::class);
            $this->call(PostsSeeder::class);
            $this->call(ShopsSeeder::class);

            $this->call(UserLikeShopsSeeder::class);
            $this->call(PostLikeUsersSeeder::class);
            $this->call(PostCommentsSeeder::class);

            $this->call(UserFollowsSeeder::class);
        }
    }
}
