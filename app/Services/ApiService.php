<?php
namespace App\Services;

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

        $response = file_get_contents($url);

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            throw new \Exception('callGnaviAreaMApi error. '.json_encode($result['error']));
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
            'category_s' => implode(',', $category_s),
            'keyid'      => Config::get('services.gnavi.key')
        ], $options);

        $query = [];
        foreach ($options as $key => $val) {
            $query[] = $key."=".$val;
        }

        $url = $base_url.$path.'?'.implode('&', $query);
        $this->_log("request url: ".$url);

        $response = file_get_contents($url);

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            throw new \Exception('callGnaviRestSearchApi error. '.json_encode($result['error']));
        }

        return $result;
    }

}