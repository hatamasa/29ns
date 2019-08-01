<?php

namespace App\Http\Controllers;

use App\Services\AreasService;
use App\Services\StationsService;
use Illuminate\Http\Request;

/**
 * 検索表示用コントローラ
 * @author hatamasa
 *
 */
class SearchController extends Controller
{

    public function __construct(StationsService $stations, AreasService $areas)
    {
        parent::__construct();
        $this->StationsService = $stations;
        $this->AreasService    = $areas;

        $this->middleware('verified')->except(['lineCompany', 'area', 'station']);
    }

    public function index(Request $request)
    {

        return view('search.index');
    }

    /**
     * 路線の選択
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function lineCompany(Request $request)
    {
        return view('search.line_company');
    }

    /**
     * 駅から探す
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function station(Request $request, $id)
    {
        $stations = $this->StationsService->getStationList($id);

        return view('search.station', compact('stations'));
    }

    /**
     * エリアから探す
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function area(Request $request)
    {
        $areas = $this->AreasService->getAreaList();

        return view('search.area', compact('areas'));
    }

}