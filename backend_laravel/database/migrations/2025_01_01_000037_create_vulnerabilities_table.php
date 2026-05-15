<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('cve_id')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('severity');
            $table->decimal('cvss_score', 3, 1);
            $table->string('cvss_vector')->nullable();
            $table->string('affected_software');
            $table->string('affected_version');
            $table->string('fixed_version')->nullable();
            $table->timestamp('published_at');
            $table->boolean('exploit_available')->default(false);
            $table->timestamps();
            
            $table->index(['severity', 'published_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vulnerabilities');
    }
};