<?php
namespace App\Repositories\Posts;

use Illuminate\Support\Facades\DB;

class PostsRepository implements PostsRepositoryInterface
{
    // ページ表示件数
    const LIST_LIMIT = 20;

    private $Posts;

    public function __construct()
    {
    }

    /**
     * IDで一覧を取得する
     * @param int $id
     * @param int $offset
     * @return object
     */
    public function getList(int $id, int $offset)
    {
        $sub_users = DB::table('users')->where('is_resigned', 0);
        $sub_groups = DB::table('groups')->where('is_deleted', 0);

        $query = DB::table('posts as p')
            ->select([
                'g.title as groups_title',
                'u.name as users_name',
                'u.thumbnail_url as thumbnail_url',
                'p.title as posts_title',
                'p.contents as posts_contents',
                'p.created_at as posts_created_at'
            ])
            ->joinSub($sub_users, 'u', function($join) {
                $join->on('p.user_id', '=', 'u.id');
            })
            ->leftJoinSub($sub_groups, 'g', function($join) {
                $join->on('p.group_id', '=', 'g.id');
            })
            ->whereIn('p.user_id', function ($query) use ($id) {
                $query->select('follow_user_id')
                ->from('user_follows')
                ->where('user_id', $id);
            })
            ->whereNull('group_id')
            ->orWhereIn('p.group_id', function($query) use ($id) {
                $query->select('group_id')
                ->from('group_users')
                ->where('user_id', $id);
            })
            ->where('p.is_deleted', 0)
            ->orderBy('p.id', 'desc')
            ->offset($offset*self::LIST_LIMIT)
            ->limit(self::LIST_LIMIT)
            ;

        return $query->get();
    }

}