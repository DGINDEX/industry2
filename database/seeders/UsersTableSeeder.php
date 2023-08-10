<?php

namespace Database\Seeders;

use App\Administrator;
use App\Company;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo 'UsersTable ... ';
        $time_start = microtime(true);

        User::flushEventListeners();
        Administrator::flushEventListeners();
        Company::flushEventListeners();
        DB::table('users')->truncate();
        DB::table('administrators')->truncate();
        DB::table('companies')->truncate();

        if (app()->environment() === 'production' || app()->environment() === 'staging') {
            $this->production();
        } else {
            $this->develop();
        }
    }

    public function develop()
    {
        $createdAt = date('Y-m-d H:i:s');

        //管理者登録
        $this->createAdminUser($createdAt);

        //個人登録
        for ($i = 1; $i <= 20; $i++) {
            $rnd  = rand(2, 4);
            $user = User::create([
                'login_id'           => 'test' . $i . '@id-frontier.jp',
                'password' => Crypt::encrypt('Admin3010'),
                'user_type'      => config('const.user.type.member'),
                'status'         => 1,
                'created_at'     => $createdAt,
            ]);
            Company::create([
                'user_id' => $user->id,
                'company_name' => 'Auto-IDフロンティア株式会社',
                'company_name_kana' => 'オートアイディ',
                'zip' => '520-3041',
                'pref_code' => 25,
                'address' =>  '栗東市出庭2031番地',
                'tel' => '077-551-2020',
                'hp_url' => 'http://id-frontier.jp',
                'representative_name' => '杉江',
                'founding_date' => date('Y-m-d', strtotime('2005-11-15')),
                'employees_num' => rand(10, 100),
                'business_content' => '事業内容',
                'comment' => '紹介文',
                'responsible_name' => '日野',
                'mail' => 'hino' . $i . '@id-frontier.jp',
                'industry_id' => rand(1, 10),
                'mail_send_flg' => 1,
                'create_user_id' => 1,
                'created_at' => date('Y-m-d')
            ]);
        }
    }

    public function production()
    {
        $createdAt = date('Y-m-d H:i:s');
        // $this->createAdminUser($createdAt);
    }


    public function createAdminUser($createdAt)
    {

        $user = User::create([
            'login_id'       => 'hino@id-frontier.jp',
            'password' => Crypt::encrypt('Aidf3010'),
            'user_type'      => config('const.user.type.administrator'),
            'status'         => 1,
            'create_user_id' => 1,
            'created_at' => $createdAt,
        ]);
        $administrator = Administrator::create([
            'user_id' => $user->id,
            'name'    => '日野',
            'name_kana' => 'ヒノ',
            'create_user_id' => 1,
            'created_at' => $createdAt
        ]);
        $user = User::create([
            'login_id'       => 'tani@id-frontier.jp',
            'password' => Crypt::encrypt('Aidf3010'),
            'user_type'      => config('const.user.type.administrator'),
            'status'         => 1,
            'create_user_id' => 1,
            'created_at' => $createdAt,
        ]);
        $administrator = Administrator::create([
            'user_id' => $user->id,
            'name'    => '谷',
            'name_kana' => 'タニ',
            'create_user_id' => 1,
            'created_at' => $createdAt
        ]);
    }
}
