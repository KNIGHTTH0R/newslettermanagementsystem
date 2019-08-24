<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject', 255);
            $table->boolean('if_terminated')->default(0);

            $table->bigInteger('from_email_id')->unsigned();
            $table->foreign('from_email_id')->references('id')->on('email_addresses');

            $table->bigInteger('reply_to_email_id')->unsigned()->nullable();
            $table->foreign('reply_to_email_id')->references('id')->on('email_addresses');

            $table->bigInteger('assigned_driver_id')->unsigned();
            $table->foreign('assigned_driver_id')->references('id')->on('drivers');

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
        Schema::dropIfExists('mails');
    }
}
