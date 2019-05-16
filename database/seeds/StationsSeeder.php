<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stations')->truncate();

        $file = new SplFileObject(base_path().'/resources/csv/station.csv');
        $file->setFlags(SplFileObject::READ_CSV);
        $data = [];
        foreach ($file as $key => $line) {
            if ($key == 0) {
                continue;
            }
            if (!isset($line[7])){
                continue;
            }
            // 東京の駅のみ処理
            if ($line[6] == 13) {
                $data[] = [
                    'station_cd' => (int)$line[0],
                    'name'       => (string)$line[2],
                    'line_cd'    => (int)$line[5],
                ];
            }
        }

        DB::table('stations')->insert($data);
    }
}
