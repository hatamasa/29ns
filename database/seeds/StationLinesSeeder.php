<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('station_lines')->truncate();
        // 東京の沿線を取得
        $response = file_get_contents('http://www.ekidata.jp/api/p/13.json');
        $result = json_decode($response, true);

        $data = [];
        foreach ($result['line'] as $line) {
            $data['line_cd'] = $line['line_cd'];
            $data['name']    = $line['line_name'];
        }

        DB::table('station_lines')->insert($data);
    }
}
