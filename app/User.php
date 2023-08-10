<?php

namespace App;

use App\Libs\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Break_;

/**
 * @property integer $id
 * @property string $login_id
 * @property string $password
 * @property boolean $user_type
 * @property boolean $status
 * @property string $auth_key
 * @property string $auth_key_expired_at
 * @property string $remember_token
 * @property string $login_server
 * @property string $login_remote_ip_address
 * @property string $login_remote_host
 * @property string $login_user_agent
 * @property string $login_referer
 * @property string $logined_at
 * @property integer $create_user_id
 * @property string $created_at
 * @property integer $update_user_id
 * @property string $updated_at
 * @property integer $delete_user_id
 * @property string $deleted_at
 */
class User extends Authenticatable
{
    use SoftDeletes;
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['login_id', 'password', 'plain_login_password', 'user_type', 'status', 'auth_key', 'auth_key_expired_at', 'remember_token', 'login_server', 'login_remote_ip_address', 'login_remote_host', 'login_user_agent', 'login_referer', 'logined_at', 'create_user_id', 'created_at', 'update_user_id', 'updated_at'];


    /**
     * 検索キー
     *
     * @var array
     */
    public static $searchKey = ['name_kana', 'status'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($user) {
            return $user->onCreatingHandler();
        });
        self::updating(function ($user) {
            return $user->onUpdatingHandler();
        });
    }

    /**
     * Model作成時の処理
     *
     * @return void
     */
    private function onCreatingHandler()
    {
        if (Auth::check()) {
            $this->create_user_id = Auth::user()->id;
            $this->update_user_id = Auth::user()->id;
        }
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * model更新時の処理
     *
     * @return void
     */
    private function onUpdatingHandler()
    {
        if (Auth::check()) {
            $this->update_user_id = Auth::user()->id;
        }
        $this->updated_at = date('Y-m-d H:i:s');
    }

    #region リレーション

    /**
     * 管理者モデルを返します
     *
     * @return void
     */
    public function administrator()
    {
        return $this->hasOne(Administrator::class, 'user_id', 'id');
    }

    /**
     * 企業モデルを返します
     *
     * @return void
     */
    public function company()
    {
        return $this->hasOne(Company::class, 'user_id', 'id');
    }
    #endregion


    /**
     * 復号されたパスワードを返します
     * @return string
     */
    public function getPlainLoginPasswordAttribute()
    {
        return Password::decrypt($this->password);
    }

    /**
     * 平文パスワードを暗号化してlogin_passwordにセットします
     * @param string $plainLoginPassword 平文パスワード
     */
    public function setPlainLoginPasswordAttribute($plainLoginPassword)
    {
        $this->password = Password::encrypt($plainLoginPassword);
    }

    /**
     * ログインパスワードを返します
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * ステータス名を返します
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return isset($this->status) ? config('const.status_name')[$this->status] : '';
    }

    /**
     * ユーザー名(管理者)を返します
     *
     * @return void
     */
    public function getAdministratorNameAttribute()
    {
        return isset($this->administrator) ? $this->administrator->name : '';
    }

    /**
     * ユーザー名(管理者)を返します
     *
     * @return void
     */
    public function getAdministratorNameKanaAttribute()
    {
        return isset($this->administrator) ? $this->administrator->name_kana : '';
    }

    /**
     * ログインIDからデータを取得(管理者)
     *
     * @param [type] $loginId
     * @return user
     */
    public static function getAdminUserByLoginId($loginId)
    {
        return Self::select('users.*')
            ->with(['administrator'])
            ->where('login_id', $loginId)
            ->where('user_type', config('const.user.type.administrator'))
            ->where('status', config('const.status.valid'))
            ->first();
    }

    /**
     * ログインIDからデータを取得(企業)
     *
     * @param [type] $loginId
     * @return user
     */
    public static function getMemberByLoginId($loginId)
    {
        return Self::select('users.*')
            ->with(['company'])
            ->where('login_id', $loginId)
            ->where('user_type', config('const.user.type.member'))
            ->where('status', config('const.status.valid'))
            ->first();
    }

    /**
     * リマインダー発行時の認証情報を保存します
     * @param type $userId
     */
    public static function saveReminderAuthInfo($userId, $authKey)
    {
        $user = self::findOrFail($userId);

        if (is_null($user)) {
            return;
        }

        $user->auth_key            = $authKey;
        $user->auth_key_expired_at = date("Y-m-d H:i:s", strtotime(config('const.reminder.expiration_hour') . " hour"));
        $user->timestamps          = false;
        $user->save();
    }

    /**
     * ユーザーの保存
     *
     * @param [type] $request
     * @param [type] $type ユーザー種別
     * @return User
     */
    public static function saveUser($request, $userId, $type)
    {
        $user = User::find($userId);

        if (!$user) {
            $user = new User();
        }
        $user->fill($request->all());
        $user->user_type = $type;
        $user->save();

        return $user;
    }

    /**
     * 検索
     *
     * @param [type] $conditions
     * @return query
     */
    public static function searchByConditions($conditions)
    {
        $query = self::select('users.*')
            ->with(['administrator'])
            ->where('user_type', config('const.user.type.administrator'));

        foreach ($conditions as $key => $value) {
            if (!in_array($key, self::$searchKey) || !isset($value)) continue;
            switch ($key) {
                case 'name_kana':
                    $query = $query->whereHas('administrator', function ($query) use ($key, $value) {
                        $query->where($key, 'LIKE', sprintf('%%%s%%', $value));
                    });
                    break;
                case 'status':
                default:
                    $query = $query->where($key, $value);
                    break;
            }
        }
        return $query;
    }
}
