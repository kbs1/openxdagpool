<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoundBlocksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('found_blocks', function (Blueprint $table) {
			$table->increments('id');
			$table->datetime('found_at');
			$table->decimal('payout', 20, 9);
			$table->decimal('fee', 20, 9);

			$table->index('found_at');

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
		Schema::dropIfExists('found_blocks');
	}
}
