<?php

/**
 * Migration file to create the user table.
 *
 * It is modified from the built-in migration file of Laravel
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            if (config('taki.username.required'))
            {
                $table->string(config('taki.field.username'))->unique();
            }
            $table->string(config('taki.field.email'))->unique();
            $table->string('password', 60);
            if (config('taki.confirm_after_created'))
            {
                $table->string('token')->nullable();
                $table->boolean('is_activated')->default(false);
            }
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
