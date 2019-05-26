<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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

        $this->middleware('auth')->except(['area']);
    }

    public function index(Request $request)
    {

        return view('Search.index');
    }

    public function station(Request $request)
    {

        return view('Search.station');
    }

    /**
     * エリアから探す
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function area(Request $request)
    {
        $areas = $this->SearchService->getList();

        return view('Search.area', compact('areas'));
    }

    public function near(Request $request)
    {

        return view('Search.near');
    }
}