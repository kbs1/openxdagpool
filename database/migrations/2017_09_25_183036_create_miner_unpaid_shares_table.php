<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinerUnpaidSharesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('miner_unpaid_shares', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('miner_id');
			$table->decimal('unpaid_shares', 20, 9);

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
		Schema::dropIfExists('miner_unpaid_shares');
	}
}
