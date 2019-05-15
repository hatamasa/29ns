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
        $this->call(UsersTableSeeder::class);
        $this->call(PostsTableSeeder::class);

//         $this->call(PasswordResetsTableSeeder::class);
//         $this->call(UserInvalidTableSeeder::class);
//         $this->call(UserLoginLogTableSeeder::class);
        $this->call(UserFollowsTableSeeder::class);
        $this->call(PostCommentsTableSeeder::class);
    }
}
