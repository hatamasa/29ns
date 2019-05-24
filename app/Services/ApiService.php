<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;

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

        $response = file_get_contents(
            $base_url.$path.'?'.http_build_query($options)
            );

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

        $response = file_get_contents(
                $base_url.$path.'?'.implode('&', $query)
            );

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            throw new \Exception('callGnaviRestSearchApi error. '.json_encode($result['error']));
        }

        return $result;
    }

}