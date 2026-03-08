<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::registerView(function ()
        {
            return view('register');
        });
        Fortify::loginView(function ()
        {
            return view('login');
        });
        RateLimiter::for('login', function (Request $request)
        {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });
        // ログイン時のバリデーションをカスタマイズ
        Fortify::authenticateUsing(function ($request)
        {
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });
    }
}