<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFoundAtMillisecondsToFoundBlocksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('found_blocks', function (Blueprint $table) {
			$table->unsignedInteger('found_at_milliseconds')->after('found_at'); // no datetime(3) or datetime(6) support in laravel unfortunately to store precise date with milliseconds...
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('found_blocks', function (Blueprint $table) {
			$table->dropColumn('found_at_milliseconds');
		});
	}
}
