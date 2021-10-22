<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\CreateNewCompany;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Fortify::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $this->multiLoginCustomize($request);
    }

    /**
     * マルチログインのカスタマイズ用メソッド
     * @return void
     */
    private function multiLoginCustomize(Request $request)
    {
        // urlからユーザーを取得
        $user = Str::of($request->path())->before('/');
        if (in_array($user, config('fortify.users'))) {
            // FortifyのviewPrefixを書き換え（各ユーザー用viewを使用）
            Fortify::viewPrefix('auth.' . $user . '.');
            config(['fortify.guard' => Str::plural($user)]);
            // ダッシュボードの切り替え
            config(['fortify.home' => '/' . $user . RouteServiceProvider::HOME]);
        }

        // ユーザー毎に登録用のクラスを分岐
        if ($user == 'user') {
            Fortify::createUsersUsing(CreateNewUser::class);
        } else {
            Fortify::createUsersUsing(CreateNewCompany::class);
        }
    }
}
