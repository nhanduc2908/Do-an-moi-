<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cloud_resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('resource_id');
            $table->string('resource_type');
            $table->string('provider');
            $table->string('region');
            $table->string('name');
            $table->json('configuration')->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('created_at_cloud')->nullable();
            $table->boolean('is_compliant')->default(true);
            $table->timestamps();
            
            $table->unique(['provider', 'resource_id']);
            $table->index(['provider', 'resource_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cloud_resources');
    }
};