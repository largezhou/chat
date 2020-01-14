<?php

namespace App\Http\Filters;

use App\Admin\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends Filter
{
    protected $simpleFilters = [
        'id',
        'username' => ['like', '%?%'],
        'name' => ['like', '%?%'],
    ];

    protected $filters = [
        'q',
    ];

    /**
     * 用户名 或 帐号 模糊查询
     *
     * @param string $val
     */
    protected function q(string $val)
    {
        $this->builder
            ->where(function (Builder $builder) use ($val) {
                $q = "%{$val}%";
                $builder->where('username', 'like', $q)
                    ->orWhere('name', 'like', $q);
            });
    }
}
