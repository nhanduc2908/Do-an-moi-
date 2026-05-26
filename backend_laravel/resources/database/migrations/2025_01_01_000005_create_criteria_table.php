<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('name');
            $table->text('description');
            $table->uuid('domain_id');
            $table->integer('weight')->default(1);
            $table->string('scoring_method')->default('manual');
            $table->decimal('max_score', 5, 2)->default(5);
            $table->decimal('min_score', 5, 2)->default(0);
            $table->decimal('passing_score', 5, 2)->default(3);
            $table->boolean('evidence_required')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
            
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
            $table->unique(['code', 'domain_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteria');
    }
};