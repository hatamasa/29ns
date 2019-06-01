<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class StationsService extends Service
{
    public function __construct()
    {
    }

    /**
     * 駅一覧を取得する
     * @return array|NULL[]
     */
    public function getStationList()
    {
        $stations = DB::table('stations')->get()->toArray();
        $list = [];
        foreach ($stations as $row) {
            // line_cdに対応するstationを詰める
            $list[$row->line_cd][] = [
                'station_cd'   => $row->station_cd,
                'station_name' => $row->name
            ];
        }

        return $list;
    }


}