<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserMinerAddressesUnique extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		foreach (\App\Users\User::all() as $user) {
			$addresses = [];

			foreach ($user->miners as $miner) {
				if (isset($addresses[$miner->address])) {
					echo 'Deleting duplicate miner ' . $miner->address . ' for user ' . $user->id . ' (' . $user->nick . ')' . "\n";
					$miner->stats()->delete();
					$miner->delete();
					continue;
				}

				$addresses[$miner->address] = true;
			}
		}

		Schema::table('miners', function (Blueprint $table) {
			$table->unique(['user_id', 'address']);
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
			$table->dropUnique('miners_user_id_address_unique');
		});
	}
}
