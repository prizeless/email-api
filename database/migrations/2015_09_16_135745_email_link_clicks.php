<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailLinkClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_link_clicks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('contact_identifier', 35);
            $table->string('link');
            $table->integer('click_count')->default(1);
            $table->integer('clicked_at')->default(0);

            $table->foreign('message_id')->references('message_id')->on('email_messages')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['contact_identifier', 'link']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_link_clicks');
    }
}
