<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmailLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmail_label', function (Blueprint $table) {
            $table->string('gmail_id');
            $table->foreignId('label_id')->constrained()->onDelete('cascade');
            $table->foreign('gmail_id')->references('id')->on('gmails')->onDelete('cascade');
            $table->primary(['gmail_id', 'label_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gmail_label');
    }
}
