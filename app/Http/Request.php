<?php

namespace App\Http;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{

    /**
     * getSortLink
     * ページネーション一覧のソートリンクを作成する
     * @param [type] $key
     * @return void
     */
    public function getSortLink($key)
    {
        $orderBy = $this->input('orderby');
        $sort    = $orderBy == $key && $this->input('sort') == 'asc' ? 'desc' : 'asc';
        $tmp     = $this->fullUrlWithQuery(['orderby' => $key, 'sort' => $sort]);
        if (config('app.env') === 'production') {
            $outputUrl = str_replace('http://', 'https://', $tmp);
        } else {
            $outputUrl = $tmp;
        }

        return $outputUrl;
    }
}
