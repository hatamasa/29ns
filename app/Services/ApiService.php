<?php
namespace App\Services;

use App\Exceptions\NotFoundShopException;
use Illuminate\Support\Facades\Config;

class ApiService extends Service
{
    const CATEGORY_YAKINIKU = 1;

    public function __construct()
    {
    }

    /**
     * ぐるなびエリアMAPIをコールする
     * @throws \Exception
     * @return mixed
     */
    public function callGnaviAreaMApi()
    {
        $base_url = Config::get('services.gnavi.base_url');
        $path     = Config::get('services.gnavi.api_path.erea');

        $options = [
            'keyid' => Config::get('services.gnavi.key')
        ];

        $url = $base_url.$path.'?'.http_build_query($options);
        $this->_log("request url: ".$url);

        $response = @file_get_contents($url);
        $result = json_decode($response, true);

        if(count($http_response_header) > 0){
            $status_code = explode(' ', $http_response_header[0]);
            if ($status_code[1] != '200') {
                throw new \Exception('callGnaviAreaMApi error. '.json_encode($result['error']));
            }
        }

        return $result;
    }

    /**
     * ぐるなび店舗検索APIをコールする
     * @param array $options
     * @param $category
     * @throws \Exception
     * @return mixed
     */
    public function callGnaviRestSearchApi(array $options, $category = null)
    {
        $base_url = Config::get('services.gnavi.base_url');
        $path     = Config::get('services.gnavi.api_path.restSearch');
        $category_s = [];
        if (!is_null($category)) {
            if (is_array($category)) {

            } else {
                switch ($category) {
                    case self::CATEGORY_YAKINIKU:
                        $category_s = [
                            Config::get("const.gnavi.category.yakiniku")[0]['category_s_code'],
                            Config::get("const.gnavi.category.yakiniku")[1]['category_s_code'],
                            Config::get("const.gnavi.category.yakiniku")[2]['category_s_code'],
                            Config::get("const.gnavi.category.yakiniku")[3]['category_s_code'],
                        ];
                        break;
                }
            }
        }

        $options = array_merge([
            'pref'       => 'PREF13',
            'category_s' => implode(',', $category_s),
            'keyid'      => Config::get('services.gnavi.key')
        ], $options);

        $query = [];
        foreach ($options as $key => $val) {
            $query[] = $key."=".$val;
        }

        $url = $base_url.$path.'?'.implode('&', $query);
        $this->_log("request url: ".$url);

        $response = @file_get_contents($url);
        $result = json_decode($response, true);

        if(count($http_response_header) > 0){
            $status_code = explode(' ', $http_response_header[0]);
            switch($status_code[1]) {
                case '400':
                case '401':
                case '405':
                case '429':
                case '500':
                    throw new \Exception('callGnaviRestSearchApi error. '.json_encode(["code" => $status_code[1], "error" => $result['error']]));
                    break;
                case '404':
                    throw new NotFoundShopException();
                    break;
                default:
            }
        }

        return $result;
    }

    /**
     * ぐるなび応援口コミAPIをコールする
     * @throws \Exception
     * @return mixed
     */
    public function callGnaviPhotoSearchApi(array $options)
    {
        $base_url = Config::get('services.gnavi.base_url');
        $path     = Config::get('services.gnavi.api_path.photoSearch');

        $options = array_merge([
            'keyid' => Config::get('services.gnavi.key'),
        ], $options);

        $query = [];
        foreach ($options as $key => $val) {
            $query[] = $key."=".$val;
        }

        $url = $base_url.$path.'?'.implode('&', $query);
        $this->_log("request url: ".$url);

        $response = @file_get_contents($url);
        $result = json_decode($response, true);

        if(count($http_response_header) > 0){
            $status_code = explode(' ', $http_response_header[0]);
            switch($status_code[1]) {
                case '400':
                case '401':
                case '405':
                case '429':
                case '500':
                    throw new \Exception('callGnaviPhotoSearchApi error. '.json_encode($result));
                    break;
                case '404':
                    throw new NotFoundShopException();
                    break;
                default:
            }
        }

        return $result;
    }

}