<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('security_scores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id');
            $table->float('overall_score');
            $table->json('category_scores');
            $table->string('trend')->nullable();
            $table->timestamp('calculated_at');
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            
            $table->index(['organization_id', 'calculated_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('security_scores');
    }
};