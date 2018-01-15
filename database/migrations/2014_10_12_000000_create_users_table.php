<?php

use Illuminate\Support\Facades\Schema;
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
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid');
			$table->string('nick')->unique();
			$table->string('email')->unique();
			$table->string('password');
			$table->string('picture')->nullable();
			$table->boolean('active')->default(false);
			$table->boolean('administrator')->default(false);
			$table->rememberToken();
			$table->timestamps();

			$table->index('uuid');
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
