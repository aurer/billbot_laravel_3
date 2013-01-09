<?php

class Create_Bills {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bills', function($table)
		{
			$table->create();
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->string('title');
		    $table->string('name');
		    $table->text('comments');
		    $table->string('amount');
		    $table->string('recurrence'); // Weekly, Monthly or Yearly
		    $table->integer('renews_on'); // Weekly = day of week, Monthly/Yearly = day of year
		    $table->boolean('send_reminder'); // Should an email reminder be sent
		    $table->integer('reminder'); // How many days before renewal to send reminder
		    $table->boolean('include_in_totals');
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
		Schema::drop('bills');
	}
}