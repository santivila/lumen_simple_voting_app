<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('votes', function(Blueprint $table)
	    {
	        $table->increments('id');
			// The resource that is going to be voted must have an "id".
			// Ex: It could be the "post id" for a wordpress site using this API to provide voting to its posts
			$table->integer('target_id')->unsigned()->index()->nullable();
			// External name of the resource that is going to be voted. Optional.
			$table->string('target_name');
			// Email address of the person that pretends to vote
			$table->string('voter_email')->index();
			// The vote is "confirmed" when voters validate their votes via a link sent to the previous email
			$table->boolean('confirmed_vote')->default(false);
			// Unique token that will be sent by mail to the voter to validate this vote
			$table->string('token')->index();
			// Timestamps
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
		Schema::drop('votes');
	}

}
