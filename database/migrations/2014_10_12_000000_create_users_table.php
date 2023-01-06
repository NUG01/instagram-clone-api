<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('fullname');
            $table->string('birth_date');
            $table->string('thumbnail')->default('assets/avatar.png');
            $table->integer('is_verified')->default(0);
            $table->string('verification_code')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('facebook_id')->nullable();
            $table->string('facebook_token')->nullable();
            $table->string('facebook_refresh_token')->nullable();
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
};
