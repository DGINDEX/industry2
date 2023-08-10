<?php

/*
 * トップ
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libs\AuthSso;
use App\User;
use App\Category;
use App\Industry;
use App\Company;
use App\Matching;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TopController extends Controller
{

    /**
     * トップ
     * @return view
     */
    public function index()
    {

        //セレクトボックス作成
        $categoryList =  Category::getCategory();
        $industryList = Industry::getIndustry();

        $companyList = Company::with('companyCategories.category')
            ->inRandomOrder()->limit(config('const.paginate_num.front_top_company'))->get(); //企業はランダム6件
        $matchingList = Matching::with(['company', 'matchingCategories.category'])
            ->where('status', config('const.matching.status.accepting'))
            ->inRandomOrder()->limit(config('const.paginate_num.front_top_matching'))->get(); //案件は受付中ランダム6件

        //リクエストデータセット
        $requestDatas = compact('companyList', 'matchingList', 'categoryList', 'industryList');

        return view('front.top.index', $requestDatas);
    }
}
