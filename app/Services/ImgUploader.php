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
    const DISP_IMG_WIDTH = '400';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 投稿の画像をS3へアップロードする
     */
    public function uploadPostsImg($request)
    {
        // アップロードしたファイルのバリデーション設定
        $request->validate([
            'files'   => 'nullable|array',
            'files.*' => 'required|file|image|mimes:jpeg,png',
        ]);

        $result = [];
        foreach ($request->file('files') as $file) {
            // アップロード完了の確認
            if (! $file->isValid()) {
                throw new RuntimeException('upload error.');
            }
            // ファイル拡張子を取得
            $ext = $file->getClientOriginalExtension();
            // ファイル名を組み立て
            $to_file_name = uniqid().'.'.$ext;
            // ファイルを保存
            $path = $file->storeAs($this->img_posts_dir, $to_file_name);
            // 生成する画像のパスを生成
            $tmp_path = storage_path('app/'.$path);

            // 画像を作成する
            $this->resizeImg($file, $tmp_path, self::DISP_IMG_WIDTH);

            // ローカル以外はs3へ画像をアップロードする
            if (env('APP_ENV') === 'local') {
                copy(storage_path('app/'.$this->img_posts_dir.'/'.$to_file_name), public_path('images/'.$this->img_posts_dir.'/'.$to_file_name));
                $result[] = asset('images/'.$this->img_posts_dir.'/'.$to_file_name);;
            } else {
                $result[] = Config::get('service.aws.url.img') . Storage::disk('s3')->putFileAs(Config::get('filesystems.disks.s3.dir.posts'), new File($tmp_path), basename($tmp_path), 'public');
            }
            // 成功したらディレクトリ配下を削除
            Storage::disk('local')->delete($this->img_posts_dir.'/'.$to_file_name);
        }

        return $result;
    }

    /**
     * 画像をリサイズする
     * @param File $file
     * @param string $path
     * @param int $size
     */
    private function resizeImg($file, $path, $size)
    {
        $image = Image::make($file);
        $image
            ->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($path);
    }

    /**
     * ユーザの画像をS3へアップロードする
     */
    public function uploadUserImg($request)
    {
        // アップロードしたファイルのバリデーション設定
        $request->validate([
            'files'   => 'nullable|array',
            'files.*' => 'required|file|image|mimes:jpeg,png',
        ]);

        $result = null;
        $file = $request->file('file');
        // アップロード完了の確認
        if (! $file->isValid()) {
            throw new RuntimeException('upload error.');
        }
        // ファイル拡張子を取得
        $ext = $file->getClientOriginalExtension();
        // ファイル名を組み立て
        $to_file_name = uniqid().'.'.$ext;
        // ファイルを保存
        $path = $file->storeAs($this->img_users_dir, $to_file_name);
        // 生成する画像のパスを生成
        $tmp_path = storage_path('app/'.$path);

        // 画像を作成する
        $this->resizeImgSquere($file, $tmp_path, self::DISP_IMG_WIDTH);

        // ローカル以外はs3へ画像をアップロードする
        if (env('APP_ENV') === 'local') {
            copy(storage_path('app/'.$this->img_users_dir.'/'.$to_file_name), public_path('images/'.$this->img_users_dir.'/'.$to_file_name));
            $result = asset('images/'.$this->img_users_dir.'/'.$to_file_name);
        } else {
            $result = Config::get('service.aws.url.img') . Storage::disk('s3')->putFileAs(Config::get('filesystems.disks.s3.dir.users'), new File($tmp_path), basename($tmp_path), 'public');
        }
        // 成功したらディレクトリ配下を削除
        Storage::disk('local')->delete($this->img_users_dir.'/'.$to_file_name);

        return $result;
    }

    /**
     * 画像を正方形にリサイズする
     * @param File $file
     * @param string $path
     * @param int $size
     */
    private function resizeImgSquere($file, $path, $size)
    {
        $image = Image::make($file);

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
            ->save($path);
    }

}