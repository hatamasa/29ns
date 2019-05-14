<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Requests')->truncate();

        $requests = ['先生募集！', '一緒にやる仲間募集！', 'アドバイスください！', '情報交換希望！'];

        $data = [];
        foreach ($requests as $request) {
            $data[] = [
                'name'       => $request,
            ];
        }

        DB::table('Requests')->insert($data);
    }
}
