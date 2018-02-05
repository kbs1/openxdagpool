<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHashrateToMinerStatsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('miner_stats', function (Blueprint $table) {
			$table->bigInteger('hashrate')->default(0)->after('unpaid_shares');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('miner_stats', function (Blueprint $table) {
			$table->dropColumn('hashrate');
		});
	}
}
