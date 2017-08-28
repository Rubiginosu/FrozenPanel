<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PanelLogins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_logins', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->text('username');
            $table->text('push_road');
            $table->text('verify_code');
            $table->text('status')->default('null');
            $table->text('request_msg')->nullable();
            $table->text('timeout')->default('false');
            $table->boolean('is_read');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
