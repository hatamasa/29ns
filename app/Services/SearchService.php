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

    public function getList()
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