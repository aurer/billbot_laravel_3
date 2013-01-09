<?php

class Create_Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->create();
		    $table->increments('id');
		    $table->string('forename', 255);
		    $table->string('surname', 255);
		    $table->string('username', 255);
		    $table->string('email', 255);
		    $table->string('password', 255);
		    $table->string('hash', 255);
		    $table->boolean('active');
		    $table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}