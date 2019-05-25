<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ApiService extends Service
{

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
     * @throws \Exception
     * @return mixed
     */
    public function callGnaviRestSearchApi(array $options)
    {
        $base_url = Config::get('services.gnavi.base_url');
        $path     = Config::get('services.gnavi.api_path.restSearch');

        $options = array_merge([
            'keyid' => Config::get('services.gnavi.key')
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