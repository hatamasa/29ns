<?php
namespace App\Services;

use App\Repositories\PostsRepository;

class PostsService extends Service
{
    private $PostsRepo;

    public function __construct(PostsRepository $posts_repository)
    {
        $this->PostsRepo = $posts_repository;
    }

    /**
     * 一覧表示データを取得する
     * @param int $id
     * @param number $offset
     * @return object
     */
    public function getList(int $id, $offset = 1)
    {
        return $this->PostsRepo->getList($id, $offset);
    }

}