<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('network_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('source_ip', 45);
            $table->string('destination_ip', 45);
            $table->string('protocol');
            $table->integer('port')->nullable();
            $table->bigInteger('packet_size')->nullable();
            $table->string('action');
            $table->uuid('rule_id')->nullable();
            $table->timestamp('logged_at');
            $table->json('details')->nullable();
            $table->timestamps();
            
            $table->index(['source_ip', 'destination_ip', 'logged_at']);
            $table->index('action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('network_logs');
    }
};