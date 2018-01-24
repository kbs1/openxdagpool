<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateFullyImportedFlagToPayoutsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payouts', function (Blueprint $table) {
			$table->boolean('date_fully_imported')->default(false)->after('made_at_milliseconds');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payouts', function (Blueprint $table) {
			$table->dropColumn('date_fully_imported');
		});
	}
}
