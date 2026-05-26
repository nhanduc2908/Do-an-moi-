<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_domain_version', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->uuid('domain_id');
            $table->string('version')->default('1.0');
            $table->json('permissions')->nullable();
            $table->timestamps();
            
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
            $table->unique(['role_id', 'domain_id', 'version']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_domain_version');
    }
};