<?php

/*
 * ログイン
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libs\AuthSso;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        AuthenticatesUsers;

    /**
     * ログイン試行回数(回)
     * @var int
     */
    protected $maxAttempts = 30;

    /**
     * ログインロックタイム(分)
     * @var int
     */
    protected $decayMinutes = 10;

    /**
     * ログイン後のリダイレクト先
     * @var varchar
     */
    protected $redirectTo = '/top';

    /**
     * Authentication Guard
     * config/auth.php参照
     */
    protected function guard()
    {
        return Auth::guard('administrators');
    }

    /**
     * 
     * @return string
     */
    public function username()
    {
        return 'login_id';
    }

    /**
     * 
     * @return string
     */
    public function password()
    {
        return 'password';
    }

    /**
     * ログイン画面
     * @note ID/PWで認証
     * @return view
     */
    public function index()
    {
        return view('login.index');
    }

    /**
     * 認証前バリデーション
     * @param Request $request
     * @throws type
     */
    protected function validateLogin(Request $request)
    {
        //パスワード未入力時のエラー
        if (!isset($request->login_id)) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.login_id_req')],
            ]);
        }

        //パスワード未入力時のエラー
        if (!isset($request->password)) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.password_req')],
            ]);
        }

        $this->validate($request, [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ]);
    }

    /**
     * 認証処理
     * @note ID/PWは入力必須。いずれも半角英数のみ入力許可
     * @param Request $request
     * @return type
     */
    public function attemptLogin(Request $request)
    {
        $loginUser = User::where('login_id', $request->login_id)->first();

        //該当のログインIDが存在しない場合
        if (!isset($loginUser)) {
            return Auth::attempt(['login_id' => $request->login_id]);
        }

        //ステータスが無効の場合
        if ($loginUser->status != config('const.status.active')) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed_status')],
            ]);
        }

        return $this->guard()->attempt([
            'login_id'   => $request->input('login_id'),
            'password'   => $request->input('password'),
            'status'     => config('const.status.active'),
            'deleted_at' => null
        ]);
    }

    /**
     * ログアウト
     * @return redirect
     */
    public function logout()
    {
        User::flushEventListeners();
        $this->guard()->logout();
        return redirect()->to('/');
    }
}
