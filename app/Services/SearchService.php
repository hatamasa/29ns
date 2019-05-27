<?php
namespace App\Services;

use App\Repositories\PostsRepository;
use App\Repositories\ShopsRepository;
use Illuminate\Support\Facades\DB;

class SearchService extends Service
{
    public function __construct(PostsRepository $posts, ShopsRepository $shops)
    {
        $this->Posts = $posts;
        $this->Shops = $shops;
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