<?php
namespace App\Filters;

use App\User;


class ThreadFilters extends Filters
{

    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * filter the query by a given username
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $userId = User::whereName($username)->firstOrFail()->id;
        return $this->builder->whereUserId($userId);
    }

    /**
     * @param $val
     * @return mixed
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}