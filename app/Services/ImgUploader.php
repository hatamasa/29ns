<?php
namespace App\Services;

use RuntimeException;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImgUploader extends Service
{
    // 画像保存一時ディレクトリ
    private $img_posts_dir = 'posts';
    private $img_users_dir = 'users';
    // 生成する表示用画像のサイズ
    const DISP_USER_IMG_WIDTH = '100';
    const DISP_POST_IMG_WIDTH = '200';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param Illuminate\Http\ $request
     * @throws RuntimeException
     * @return string|\Illuminate\Http\false
     */
    public function tmpUploadPostImg($input)
    {
        // base64をデコード
        $pos = strpos($input['file'], 'base64,');
        $file_data = str_replace(' ', '+', substr($input['file'], $pos + 7));
        $file = base64_decode($file_data);

        // ファイル拡張子を取得
        $ext = substr($input['filename'], strrpos($input['filename'], '.')+1);
        $tmp_path = $this->img_posts_dir.'/'.uniqid().'.'.$ext;
        $target_path = storage_path('app/'.$tmp_path);

        // ファイルを保存
        file_put_contents($target_path, $file);

        return $tmp_path;
    }

    /**
     * 投稿の画像をS3へアップロードする
     */
    public function uploadPostsImg($tmp_paths)
    {
        $result = [];

        foreach ($tmp_paths as $tmp_path) {
            if (empty($tmp_path)) {
                continue;
            }
            // 生成する画像のパスを生成
            $tmp_full_path = storage_path('app/'.$tmp_path);

            // 画像を作成する
            $this->resizeImg($tmp_full_path, self::DISP_POST_IMG_WIDTH);

            // ローカル以外はs3へ画像をアップロードする
            if (env('APP_ENV') === 'local') {
                copy(storage_path('app/'.$tmp_path), public_path('images/'.$tmp_path));
                $result[] = asset('images/'.$tmp_path);;
            } else {
                $result[] = Config::get('services.aws.url.img') . Storage::disk('s3')->putFileAs(Config::get('filesystems.disks.s3.dir.posts'), new File($tmp_full_path), basename($tmp_full_path), 'public');
            }
            // 成功したらディレクトリ配下を削除
            Storage::disk('local')->delete($tmp_path);
        }

        return $result;
    }

    /**
     * 画像をリサイズする
     * @param File $file
     * @param string $path
     * @param int $size
     */
    private function resizeImg($tmp_path, $size)
    {
        $image = Image::make($tmp_path);
        $image
            ->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($tmp_path);
    }

    /**
     *
     * @param Illuminate\Http\ $request
     * @throws RuntimeException
     * @return string|\Illuminate\Http\false
     */
    public function tmpUploadUserImg($input)
    {
        // base64をデコード
        $pos = strpos($input['file'], 'base64,');
        $file_data = str_replace(' ', '+', substr($input['file'], $pos + 7));
        $file = base64_decode($file_data);

        // ファイル拡張子を取得
        $ext = substr($input['filename'], strrpos($input['filename'], '.')+1);
        $tmp_path = $this->img_users_dir.'/'.uniqid().'.'.$ext;
        $target_path = storage_path('app/'.$tmp_path);

        // ファイルを保存
        file_put_contents($target_path, $file);

        return $tmp_path;
    }

    /**
     * ユーザの画像をS3へアップロードする
     */
    public function uploadUserImg($tmp_path)
    {
        $result = null;
        // 生成する画像のパスを生成
        $tmp_full_path = storage_path('app/'.$tmp_path);

        // 画像を作成する
        $this->resizeImgSquere($tmp_full_path, self::DISP_USER_IMG_WIDTH);

        // ローカル以外はs3へ画像をアップロードする
        if (env('APP_ENV') === 'local') {
            copy(storage_path('app/'.$tmp_path), public_path('images/'.$tmp_path));
            $result = asset('images/'.$tmp_path);
        } else {
            $result = Config::get('services.aws.url.img') . Storage::disk('s3')->putFileAs(Config::get('filesystems.disks.s3.dir.users'), new File($tmp_full_path), basename($tmp_full_path), 'public');
        }
        // 成功したらディレクトリ配下を削除
        Storage::disk('local')->delete($tmp_path);

        return $result;
    }

    /**
     * 画像を正方形にリサイズする
     * @param File $file
     * @param string $path
     * @param int $size
     */
    private function resizeImgSquere($tmp_path, $size)
    {
        $image = Image::make($tmp_path);

        $height = $image->height();
        $width = $image->width();
        $resize_size = null;
        if ($height == $width) {
            $resize_size = $size;
        }
        if ($height > $width) {
            $resize_size = $size;
        }
        if ($height < $width) {
            $resize_size = $size / $height * $width;
        }

        $image
            ->resize($resize_size, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop($size, $size)
            ->save($tmp_path);
    }

}