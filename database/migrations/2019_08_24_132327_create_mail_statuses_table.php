<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 255);
            $table->text('details')->nullable();

            $table->bigInteger('driver_id')->unsigned();
            $table->foreign('driver_id')->references('id')->on('drivers');

            $table->bigInteger('mail_id')->unsigned();
            $table->foreign('mail_id')->references('id')->on('mails');

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
        Schema::dropIfExists('mail_statuses');
    }
}
