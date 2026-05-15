<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('alert_name');
            $table->string('severity');
            $table->string('status')->default('new');
            $table->string('source');
            $table->text('message');
            $table->timestamp('triggered_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->uuid('acknowledged_by')->nullable();
            $table->uuid('correlation_rule_id')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
            
            $table->foreign('acknowledged_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['severity', 'status', 'triggered_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alerts');
    }
};