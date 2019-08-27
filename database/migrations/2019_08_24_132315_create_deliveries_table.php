<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message_id', 50)->nullable()->unique();

            $table->bigInteger('mail_id')->unsigned();
            $table->foreign('mail_id')->references('id')->on('mails')->onDelete('cascade');

            $table->bigInteger('to_email_id')->unsigned();
            $table->foreign('to_email_id')->references('id')->on('email_addresses')->onDelete('cascade');

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
        Schema::dropIfExists('deliveries');
    }
}
