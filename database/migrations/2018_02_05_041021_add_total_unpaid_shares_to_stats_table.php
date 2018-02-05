<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalUnpaidSharesToStatsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stats', function (Blueprint $table) {
			$table->decimal('total_unpaid_shares', 20, 9)->default(0)->after('pool_hashrate');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stats', function (Blueprint $table) {
			$table->dropColumn('total_unpaid_shares');
		});
	}
}
