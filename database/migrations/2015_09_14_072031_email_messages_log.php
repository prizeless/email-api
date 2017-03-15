<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailMessagesLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('sg_message_id')->default(0);
            $table->string('contact_identifier', 35);
            $table->string('member_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->enum('status', ['sent', 'processed', 'delivered', 'open', 'dropped', 'bounce', 'deferred', 'spamreport']);
            $table->string('status_reason')->nullable();
            $table->integer('delivered_at')->default(0);
            $table->integer('opened_at')->default(0);
            $table->integer('created_at')->default(0);
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
        Schema::drop('email_messages');
    }
}
