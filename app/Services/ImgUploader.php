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
    private $tmp_img_dir = 'tmp/img';
    // 生成するサムネイルのサイズ
    const THUMBNAIL_WIDTH = '150';
    // 生成する表示用画像のサイズ
    const DISP_IMG_WIDTH = '400';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 画像をS3へアップロードする
     */
    public function upload($request)
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
            $path = $file->storeAs($this->tmp_img_dir, $to_file_name);
            // 生成する画像のパスを生成
            $tmp_path = storage_path('app/'.$path);
            $tmp_thumbnail_path = dirname($tmp_path).'/thumbnail_'.basename($tmp_path);
            // サムネイルと表示用画像を作成する
            $image = Image::make($file);
            $image->resize(self::THUMBNAIL_WIDTH, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($tmp_thumbnail_path);
            $image->resize(self::DISP_IMG_WIDTH, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($tmp_path);

            //画像のアップロード
            $result[] = Storage::disk('s3')->putFileAs(Config::get('filesystems.disks.s3.dir.posts'), new File($tmp_path), basename($tmp_path), 'public');
            Storage::disk('s3')->putFileAs(Config::get('filesystems.disks.s3.dir.posts'), new File($tmp_thumbnail_path), basename($tmp_thumbnail_path), 'public');
            // 成功したらディレクトリ配下を削除
            Storage::disk('local')->delete($this->tmp_img_dir.'/'.$to_file_name);
            Storage::disk('local')->delete($this->tmp_img_dir.'/thumbnail_'.$to_file_name);
        }

        return $result;
    }

}