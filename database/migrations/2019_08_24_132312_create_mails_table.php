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
            $table->text('html_content')->nullable();
            $table->text('text_content')->nullable();

            $table->bigInteger('from_email_id')->unsigned();
            $table->foreign('from_email_id')->references('id')->on('email_addresses')->onDelete('cascade');

            $table->bigInteger('reply_to_email_id')->unsigned()->nullable();
            $table->foreign('reply_to_email_id')->references('id')->on('email_addresses')->onDelete('cascade');

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
