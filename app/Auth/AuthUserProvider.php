<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Auth\EloquentUserProvider;
use App\Libs\Password;

class AuthUserProvider extends EloquentUserProvider
{
    /**
     * パスワードを復号して比較します
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];
        return $plain == Password::decrypt($user->getAuthPassword()) ? true : false;
    }
}
