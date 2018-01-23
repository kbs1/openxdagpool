<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCacheToMinersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('miners', function (Blueprint $table) {
			$table->string('status', 20)->default('offline')->after('note');
			$table->text('ip_and_port')->after('status')->nullable();
			$table->unsignedInteger('machines_count')->default(0)->after('ip_and_port');
			$table->bigInteger('hashrate')->default(0)->after('machines_count');
			$table->decimal('unpaid_shares', 20, 6)->default(0)->after('hashrate');
			$table->decimal('balance', 20, 9)->default(0)->after('unpaid_shares');
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
			$table->dropColumn('status');
			$table->dropColumn('ip_and_port');
			$table->dropColumn('machines_count');
			$table->dropColumn('hashrate');
			$table->dropColumn('unpaid_shares');
			$table->dropColumn('balance');
		});
	}
}
