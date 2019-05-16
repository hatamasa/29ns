# 29ns
東京29NSリポジトリ
作成 2019/5/15

# API
* 東京の沿線取得API
 * http://www.ekidata.jp/api/p/13.json

# csv
* 駅データcsv
 * https://www.ekidata.jp/dl/?p=1

# 環境作成後必要なコマンド
* composer install
* .env作成
* API_KEY作成
 * php artisan key:generate
* JWT認証キー作成
 * php artisan jwt:secret
