<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAverageHashrateToMinersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('miners', function (Blueprint $table) {
			$table->bigInteger('average_hashrate')->default(0)->after('hashrate');
		});

		\DB::statement('update miners set average_hashrate = hashrate');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('miners', function (Blueprint $table) {
			$table->dropColumn('average_hashrate');
		});
	}
}
