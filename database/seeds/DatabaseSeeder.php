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
        $this->call(ShopsSeeder::class);

        $this->call(UsersTableSeeder::class);
        $this->call(UserFollowsSeeder::class);
        $this->call(UserLikeShopsSeeder::class);

        $this->call(PostsSeeder::class);
        $this->call(PostLikeUsersSeeder::class);
        $this->call(PostCommentsSeeder::class);

        $this->call(AreasSeeder::class);
        $this->call(StationLinesSeeder::class);
        $this->call(StationsSeeder::class);
    }
}
