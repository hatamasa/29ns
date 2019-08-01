<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class StationsService extends Service
{
    const LINE_COMPANY_JR = 1;
    const LINE_COMPANY_PRIVATE = 2;
    const LINE_COMPANY_METRO = 3;

    public function __construct()
    {
    }

    /**
     * 駅一覧を取得する
     * @return array|NULL[]
     */
    public function getStationList($company_cd)
    {
        $stations = DB::table('stations')->get()->toArray();
        $list = [];
        $station_lines = Config::get('const.station_line');
        foreach ($stations as $row) {
            // 指定された路線のみ処理する
            if ($station_lines[$row->line_cd]['company'] == $company_cd) {
                // line_cdに対応するstationを詰める
                $list[$row->line_cd][] = [
                    'station_cd'   => $row->station_cd,
                    'station_name' => $row->name
                ];
            }
        }

        return $list;
    }


}