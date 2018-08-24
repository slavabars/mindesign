<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('id')->unique('products_id_uindex');
			$table->string('title')->nullable();
			$table->string('image', 500)->nullable();
			$table->text('description', 65535)->nullable();
			$table->timestamp('first_invoice')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('url', 500)->nullable();
			$table->integer('price')->nullable();
			$table->integer('amount')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
