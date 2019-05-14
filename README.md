# nrt_sns
僕らのsnsリポジトリ
最終更新 2019/4/6

## 各ローカル開発環境作成
* githubアカウント→作成したら波多野に共有
* MAMP(php7以上でとりま最新で)
* git & gitクライアント(SourceTree)
* IDE(eclipseとか好きなやつで)
* MySQLWorkBench(あると便利。MAMPのmysqlに直接ターミナルで接続でもOK)

## 開発方針(わからない人はググって)
* リポジトリ(githubアカウントあればcloneできる)：https://github.com/hatamasa/nrt_sns
* リポジトリをcloneして開発する
* 開発後、機能毎でプルリクエストを発行するgithubフロー
* 必要な他者の変更はマージしてローカル環境で開発を進める

## clone後の作業

```
# プロジェクトルートへ移動
$ cd nrt_sns

# composerをインストールした状態で
$ composer install

# nrt_sns/.env (コミットしちゃダメ)
# db接続情報を個々の環境の情報へ変更
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nrt_sns
DB_USERNAME=root
DB_PASSWORD=root

# ここら辺を参照してメール送信を追加
# https://qiita.com/zaburo/items/37f28f0b621cbac74d15
# gmailで二段階認証を使用している場合
# https://teratail.com/questions/120062
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=xxxxxx@gmail.com
MAIL_PASSWORD=xxxxxx
MAIL_ENCRYPTION=tls

# プロジェクトルートへ移動
$ cd nrt_sns

# テーブル作成
$ php artisan migrate
```

* MAMPをスタートさせてページが開ければOK
* ログイン、会員登録は実装済みのため各自会員登録を行ってログインすること
