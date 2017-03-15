<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpamReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_spam_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('contact_identifier', 35);
            $table->integer('report_count')->default(1);
            $table->integer('reported_at')->default(0);

            $table->foreign('message_id')->references('message_id')->on('email_messages')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['message_id', 'contact_identifier']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_spam_reports');
    }
}
