<?php

namespace App\Admin\Filters;

class UserFilter extends Filter
{
    protected $simpleFilters = [
        'id',
        'username' => ['like', '%?%'],
        'name' => ['like', '%?%'],
    ];
}
