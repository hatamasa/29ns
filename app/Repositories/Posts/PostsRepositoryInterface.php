<?php
namespace App\Repositories\Posts;

interface PostsRepositoryInterface
{
    /**
     * IDで一覧を取得する
     * @param int $id
     * @param int $offset
     * @return object
     */
    public function getList(int $id, int $offset);
}