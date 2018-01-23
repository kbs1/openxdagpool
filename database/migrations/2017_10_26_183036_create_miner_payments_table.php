<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinerPaymentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('miner_payments', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('miner_id');
			$table->datetime('made_at');
			$table->unsignedInteger('made_at_milliseconds'); // no datetime(3) or datetime(6) support in laravel unfortunately to store precise date with milliseconds...
			$table->string('tag', 40);
			$table->string('sender', 32);
			$table->decimal('amount', 20, 9);

			$table->foreign('miner_id')->references('id')->on('miners');

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
		Schema::dropIfExists('miner_payments');
	}
}
