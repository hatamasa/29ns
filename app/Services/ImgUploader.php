<?php
namespace App\Services;

use Aws\S3\S3Client;
use RuntimeException;
use Aws\Credentials\Credentials;
use Illuminate\Support\Facades\Config;

class ImgUploader extends Service
{
    // 扱うファイルの拡張子
    private $ext;
    // 画像保存一時ディレクトリ
    private $tmp_img_dir = '/tmp/img';
    // アップロードするファイル名
    private $to_file_name;
    // サムネの一時パス
    private $tmp_thumbnail_path;
    // 画像の一時パス
    private $tmp_disp_img_path;
    // 生成するサムネイルのサイズ
    const THUMBNAIL_WIDTH = '150';
    // 生成する表示用画像のサイズ
    const DISP_IMG_WIDTH = '350';
    // S3への接続情報
    private $s3client;

    public function __construct()
    {
        parent::__construct();
        $this->s3client = new S3Client([
            'credentials' => new Credentials(Config::get("services.aws.access_key"), Config::get("services.aws.secret_key")),
            'region'      => Config::get("services.aws.region"),
            'version'     => 'latest',
        ]);
    }

    /**
     * 画像をS3へアップロードする
     * @throws FileUploadExecption
     */
    public function upload($request)
    {
        // アップロードしたファイルのバリデーション設定
        $this->validate($request, [
            'files' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png',
                'dimensions:min_width=100,min_height=100,max_width=600,max_height=600',
            ]
        ]);
        if (! $request->file('files')->isValid([])) {
            throw new RuntimeException('upload error.');
        }

        if(!file_exists($this->tmp_img_dir) && !mkdir($this->tmp_img_dir, 0700)){
            throw new RuntimeException('Not Found or Not Make tmp_img_dir.');
        }

        $result = [];
        // TODO: flysystemで書き換える
        foreach ($request->file('files') as $file) {
            // ファイル拡張子を取得
            $this->ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            // ファイル名を組み立て
            $this->to_file_name = uniqid().'.'.$this->ext;
            // サムネイルと表示用画像を作成
            $this->createDispAndThumb($file["tmp_name"]);

            //画像のアップロード
            $this->s3PutObject($this->tmp_thumbnail_path, 'thumbnail_' . $this->to_file_name);
            $result[] = $this->s3PutObject($this->tmp_disp_img_path, $this->to_file_name);
            // 成功したらディレクトリ配下を削除
            unlink($this->tmp_thumbnail_path);
            unlink($this->tmp_disp_img_path);
        }

        return $result;
    }

    /**
     * サムネイルと表示用画像を作成する
     * @param $original_file
     * @param $to_file_name
     */
    private function createDispAndThumb($tmp_name)
    {
        // 生成する画像のパスを生成
        $this->tmp_thumbnail_path = $this->tmp_img_dir . '/thumbnail_' . $this->to_file_name;
        $this->tmp_disp_img_path = $this->tmp_img_dir . '/' . $this->to_file_name;
        // サムネイルと表示用画像を作成する
        $this->resizeImg($tmp_name, $this->tmp_thumbnail_path, self::THUMBNAIL_WIDTH);
        $this->resizeImg($tmp_name, $this->tmp_disp_img_path, self::DISP_IMG_WIDTH);
    }

    /**
     * リサイズ画像を作成する
     * @param $original_file
     * @param $to_file_path
     * @param $width
     */
    private function resizeImg($original_file, $to_file_path, $width)
    {
        list($original_width, $original_height) = getimagesize($original_file);
        // 縦横比はそのままで空の画像を作成
        $height = round( $original_height * $width / $original_width );
        $image = imagecreatetruecolor($width, $height);
        // オリジナルコピー画像を空画像にマージ
        if($this->ext === 'jpg' || $this->ext === 'jpeg') $original_image = imagecreatefromjpeg($original_file);
        if($this->ext === 'png') $original_image = imagecreatefrompng($original_file);
        if($this->ext === 'gif') $original_image = imagecreatefromgif($original_file);
        imagecopyresized($image, $original_image, 0, 0, 0, 0,
            $width, $height, $original_width, $original_height);
        // ディレクトリに画像を保存
        if($this->ext === 'jpg' || $this->ext === 'jpeg') imagejpeg($image, $to_file_path);
        if($this->ext === 'png') imagepng($image, $to_file_path);
        if($this->ext === 'gif') imagegif($image, $to_file_path);
    }

    /**
     * S3へファイルをPUTする
     * @param $image 保存元画像パス
     * @param $to_file_name 保存先画像パス
     * @return $result
     */
    private function s3PutObject($source_file, $to_file_name)
    {
        $result = $this->s3client->putObject([
                    'Bucket'      => Config::get("services.aws.bucket.posts"),
                    'Key'         => $to_file_name,
                    'ContentType' => mime_content_type($source_file),
                    'SourceFile'  => $source_file,
                    'ACL'         => 'public-read',
                ]);

        $statusCode = $result['@metadata']['statusCode'];
        if ($statusCode !== 200) {
            throw new \Exception('response error. [statusCode: '.$statusCode.']');
        }
        return $result['ObjectURL'];
    }

}