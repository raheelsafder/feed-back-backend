<?php

namespace App\Filters\FeedBack;

use Closure;

class FeedBackNameFilter
{
    public function handle($query, Closure $next)
    {
        if (request()->input('name') != null) {
            $query->where('title', 'like', '%' . request()->input('name') . '%');
        }
        return $next($query);
    }
}
