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
        $this->call(GroupsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(RequestsTableSeeder::class);
        $this->call(ThreadsTableSeeder::class);

        $this->call(PasswordResetsTableSeeder::class);
        $this->call(UserInvalidTableSeeder::class);
        $this->call(UserLoginLogTableSeeder::class);
        $this->call(UserProfileTableSeeder::class);
        $this->call(UserFollowsTableSeeder::class);
        $this->call(GroupUsersTableSeeder::class);
        $this->call(GroupTagsTableSeeder::class);
        $this->call(PostRequestsTableSeeder::class);
        $this->call(PostTagsTableSeeder::class);
        $this->call(PostCommentsTableSeeder::class);
        $this->call(UserThreadsTableSeeder::class);
        $this->call(ThreadMessagesTableSeeder::class);
        $this->call(RecommendUsersTableSeeder::class);
        $this->call(RecommendGroupsTableSeeder::class);
    }
}
