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

        $this->middleware('verified')->except(['area', 'station']);
    }

    public function index(Request $request)
    {

        return view('Search.index');
    }

    /**
     * 駅から探す
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function station(Request $request)
    {
        $stations = $this->StationsService->getStationList();

        return view('Search.station', compact('stations'));
    }

    /**
     * エリアから探す
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function area(Request $request)
    {
        $areas = $this->AreasService->getAreaList();

        return view('Search.area', compact('areas'));
    }

}