<?php

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SocialiteController;
use App\Models\Discussion;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home')
    ->name('home');

Route::get('discussion/{discussion}/{slug}', function (Discussion $discussion) {
    return view('discussion', compact('discussion'));
})->name('discussion');

Route::redirect('/redirect-to-home', '/')
    ->name('login');

Route::view('/registered', 'registered')
    ->name('verification.notice')
    ->middleware('just-registered');

Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::get('/reset-password/{token}', function (string $token) {
    return view('password-reset', compact('token'));
})->middleware('guest')
    ->name('password.reset');

Route::get("redirect/{provider}", [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');

Route::get("callback/{provider}", [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

Route::view('tags', 'tags')
    ->name('tags');

Route::get('tag/{tag}/{slug}', function (Tag $tag) {
    return view('tag', compact('tag'));
})
    ->name('tag');

Route::get('search', function () {
    if (!request('q')) {
        return redirect()->route('home');
    }
    return view('search');
})
    ->name('search');

Route::middleware(['auth', 'verified'])
    ->group(function () {

        Route::group(
            ['as' => 'profile.', 'prefix' => 'profile'],
            function () {

                Route::view('/', 'profile.index')
                    ->name('index');

                Route::view('/settings', 'profile.settings')
                    ->name('settings');

                Route::view('/replies', 'profile.replies')
                    ->name('replies');

                Route::view('/best-replies', 'profile.best-replies')
                    ->name('best-replies');

                Route::view('/discussions', 'profile.discussions')
                    ->name('discussions');

                Route::view('/likes', 'profile.likes')
                    ->name('likes');

                Route::view('/comments', 'profile.comments')
                    ->name('comments');

            }
        );

        Route::get('logout', LogoutController::class)
            ->name('logout');

        Route::get('user/{user}/{slug}', function (User $user) {
            return view('user', compact('user'));
        })
            ->name('user');

    });
