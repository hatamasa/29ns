
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\ApiService;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->truncate();
        // エリアMAPI呼び出し
        $result = (new ApiService())->callGnaviAreaMApi();

        $data = [];
        foreach ($result['garea_middle'] as $row) {
            // 東京の場合のみエリアマスタとする
            if ($row['pref']['pref_code'] == "PREF13") {
                $data[] = [
                    'area_cd'   => (string)$row['areacode_m'],
                    'area_l_cd' => (string)$row['garea_large']['areacode_l'],
                    'name'      => (string)$row['areaname_m'],
                ];
            }
        }

        DB::table('areas')->insert($data);
    }
}
