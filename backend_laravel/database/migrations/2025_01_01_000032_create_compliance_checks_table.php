<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compliance_checks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('standard_code');
            $table->string('control_id');
            $table->string('control_name');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->string('implementation_status')->default('not_started');
            $table->text('evidence_path')->nullable();
            $table->string('owner')->nullable();
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamp('next_review_at')->nullable();
            $table->timestamps();
            
            $table->index(['standard_code', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('compliance_checks');
    }
};