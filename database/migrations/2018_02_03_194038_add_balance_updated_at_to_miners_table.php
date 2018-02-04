<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBalanceUpdatedAtToMinersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('miners', function (Blueprint $table) {
			$table->datetime('balance_updated_at')->nullable()->after('balance');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('miners', function (Blueprint $table) {
			$table->dropColumn('balance_updated_at');
		});
	}
}
