<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('miners', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid')->unique();
			$table->unsignedInteger('user_id');
			$table->string('address');
			$table->boolean('email_alerts')->default(false);

			$table->foreign('user_id')->references('id')->on('users');

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
		Schema::dropIfExists('miners');
	}
}
