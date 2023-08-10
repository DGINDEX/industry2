<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('login_id', 100)->comment('ログインID(メールアドレス)');
            $table->string('password', 255)->comment('ログインパスワード');
            $table->unsignedTinyInteger('user_type')->comment('種別');
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->string('auth_key', 255)->nullable()->comment('認証キー');
            $table->timestamp('auth_key_expired_at')->nullable()->comment('認証キー有効期限');
            $table->string('remember_token', 255)->nullable()->comment('ログインCookie情報');
            $table->string('login_server', 255)->nullable()->comment('ログインサーバー');
            $table->string('login_remote_ip_address', 45)->nullable()->comment('ログインリモートIP');
            $table->string('login_remote_host', 255)->nullable()->comment('ログインリモートホスト');
            $table->text('login_user_agent')->nullable()->comment('ログインユーザーエージェント');
            $table->text('login_referer')->nullable()->comment('ログインリファラー');
            $table->timestamp('logined_at')->nullable()->comment('ログイン日');
            $table->bigInteger('create_user_id')->nullable()->comment('作成者');
            $table->timestamp('created_at')->comment('作成日');
            $table->bigInteger('update_user_id')->nullable()->comment('更新者');
            $table->timestamp('updated_at')->comment('更新日');
            $table->bigInteger('delete_user_id')->nullable()->comment('削除者');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
            $table->index('login_id', 'idx_login_id');
            $table->index(['user_type', 'status'], 'idx_user_type_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
