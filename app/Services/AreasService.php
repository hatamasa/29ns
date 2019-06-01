<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class AreasService extends Service
{
    public function __construct()
    {
    }

    /**
     * エリア一覧を取得する
     * @return array|NULL[]
     */
    public function getAreaList()
    {
        $areas = DB::table('areas')->get()->toArray();
        $list = [];
        foreach ($areas as $row) {
            // areaLに対応するareaMを詰める
            $list[$row->area_l_cd][] = [
                    'area_cd'   => $row->area_cd,
                    'area_name' => $row->name
                ];
        }

        return $list;
    }


}