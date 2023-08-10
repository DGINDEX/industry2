<?php

namespace App\Libs;

use App\User;
use SplFileObject;
use Illuminate\Support\Facades\File;

/**
 * 汎用処理用クラス
 */
class Utility
{
    /**
     * 検索条件引継ぎ用のパラメーター取得処理
     * @param type $referer
     * @return type
     */
    public static function getSearchParams($referer)
    {
        $returnParams = null;
        if (strpos($_SERVER['HTTP_REFERER'], '?') !== false) {
            $returnParams = strstr($_SERVER['HTTP_REFERER'], '?');
        }
        return $returnParams;
    }

    /**
     * フォルダがあるかチェックしてなければ作成します
     *
     * @param string $folderPath
     * @param boolean $overWrite
     * @return boolean
     */
    public static function checkAndCreateFolder($folderPath, $overWrite = true)
    {
        $result = true;
        if (!file_exists($folderPath)) {
            $result = File::makeDirectory($folderPath, 0775, $overWrite);
        }
        return $result;
    }

    /**
     * 対象のフォルダ内を全て削除します
     *
     * @param [type] $deleteFolderPath
     * @return void
     */
    public static function deleteFolder($deleteFolderPath)
    {
        $result = true;
        if (file_exists($deleteFolderPath)) {
            $result = File::deleteDirectory($deleteFolderPath);
        }
        return $result;
    }

    /**
     * csvファイルを読み込みます
     * @param string $filePath csvファイルパス
     * @param bool $isTsv Tsvファイルかどうか
     * @return \App\Libs\SplFileObject|array
     */
    public static function readCsvData(string $filePath, bool $isTsv = false)
    {
        $results = [];

        //ファイルチェック(無い場合は空の配列を返して終了)
        if (!file_exists($filePath)) {
            return $results;
        }

        $file = new SplFileObject($filePath);

        //csv配列モードへ変更
        $file->setFlags(SplFileObject::READ_CSV);
        if ($isTsv) {
            $file->setCsvControl("\t");
        }

        foreach ($file as $line) {
            // 空行をスキップする
            if (is_null($line[0])) {
                continue;
            }
            //SJISの場合はUTF-8に変換する
            mb_convert_variables("UTF-8", "SJIS-win", $line);
            $results[] = $line;
        }

        return $results;
    }
}
