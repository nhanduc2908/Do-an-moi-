<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->integer('level')->default(0);
            $table->integer('weight')->default(1);
            $table->string('status')->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('domains')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('domains');
    }
};