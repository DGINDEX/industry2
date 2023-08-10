<?php

namespace App\Libs;

use Illuminate\Support\Facades\Crypt;

/**
 * Description of Password
 *
 * @author hasegawa
 */
class Password
{

    /**
     * パスワードを暗号化します
     * @param string $plainPassword 生のパスワード
     * @return string 暗号化されたパスワード
     */
    public static function encrypt($plainPassword)
    {
        return Crypt::encrypt($plainPassword);
    }

    /**
     * 暗号化されたパスワードを複合します
     * @param string $password 暗号化されたパスワード
     * @return string 複合されたパスワード
     */
    public static function decrypt($password)
    {
        return Crypt::decrypt($password);
    }

    /**
     * 生のパスワードと暗号化されたパスワードが正しいかチェックします
     * @param string $plainPassword 生のパスワード
     * @param string $password 暗号化されたパスワード
     * @return boolean 一致する場合はtrue、そうでない場合はfalseを返します
     */
    public static function isCorrect($plainPassword, $password)
    {
        return $plainPassword === static::decrypt($password);
    }

    /**
     * パスワードの自動生成行う
     *
     * @param integer $length
     * @return void
     */
    public static function generatePassword($length = 8)
    {
        //vars
        $pwd         = array();
        $pwd_strings = array(
            "sletter" => range('a', 'z'),
            "cletter" => range('A', 'Z'),
            "number"  => range('0', '9'),
        );

        //logic
        while (count($pwd) < $length) {
            // 4種類必ず入れる
            if (count($pwd) < 3) {
                $key = key($pwd_strings);
                next($pwd_strings);
            } else {
                // 後はランダムに取得
                $key = array_rand($pwd_strings);
            }
            $pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
        }
        // 生成したパスワードの順番をランダムに並び替え
        shuffle($pwd);

        return implode($pwd);
    }
}
