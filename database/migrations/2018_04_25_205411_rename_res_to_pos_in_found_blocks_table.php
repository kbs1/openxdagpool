<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameResToPosInFoundBlocksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('found_blocks', function (Blueprint $table) {
			\DB::statement('ALTER TABLE found_blocks CHANGE res pos VARCHAR(16) NOT NULL');
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
			\DB::statement('ALTER TABLE found_blocks CHANGE pos res VARCHAR(16) NOT NULL');
		});
	}
}
