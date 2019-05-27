<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

/**
 * 検索表示用コントローラ
 * @author hatamasa
 *
 */
class SearchController extends Controller
{

    public function __construct(SearchService $search)
    {
        parent::__construct();
        $this->SearchService = $search;

        $this->middleware('auth')->except(['area', 'station']);
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
        $stations = $this->SearchService->getStationList();

        return view('Search.station', compact('stations'));
    }

    /**
     * エリアから探す
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function area(Request $request)
    {
        $areas = $this->SearchService->getAreaList();

        return view('Search.area', compact('areas'));
    }

    public function near(Request $request)
    {

        return view('Search.near');
    }
}