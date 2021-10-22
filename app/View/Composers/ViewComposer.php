<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ViewComposer
{
    public function compose(View $view)
    {
        $user = '';
        $prefix = '';
        
        foreach (config('fortify.users') as $guard) {
            if (Auth::guard(Str::plural($guard))->check()) {
                $user = Auth::guard(Str::plural($guard))->user();
                $prefix = $guard . '.';
            }
        }

        return $view->with('user', $user)->with('prefix', $prefix);
    }
}
