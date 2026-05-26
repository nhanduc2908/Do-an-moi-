<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sync_type');
            $table->string('status');
            $table->integer('items_synced')->default(0);
            $table->text('error_message')->nullable();
            $table->json('details')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['sync_type', 'status', 'started_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sync_logs');
    }
};