<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incident_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('incident_id');
            $table->uuid('user_id');
            $table->text('comment');
            $table->timestamp('created_at');
            $table->timestamps();
            
            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['incident_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('incident_comments');
    }
};