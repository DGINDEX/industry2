<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Illuminate\Http\Request;

/**
 * ログイン成功時のイベントリスナー
 */
class LogSuccessfulLogin
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $request = app('request');
        User::flushEventListeners();

        /* @var $user User */
        $user                          = $event->user;
        $user->login_server            = $request->server('HTTP_HOST');
        $user->login_user_agent        = $request->server('HTTP_USER_AGENT');
        $user->login_referer           = $request->server('HTTP_REFERER');
        $user->login_remote_host       = $request->server('REMOTE_HOST');
        $user->login_remote_ip_address = $this->getUserIP($request);
        $user->logined_at              = date('Y-m-d H:i:s');
        $user->timestamps              = false;
        $user->save();
    }

    private function getUserIP(Request $request)
    {
        $client  = $request->server('HTTP_CLIENT_IP');
        $forward = $request->server('HTTP_X_FORWARDED_FOR');
        $remote  = $request->server('REMOTE_ADDR');

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
}
